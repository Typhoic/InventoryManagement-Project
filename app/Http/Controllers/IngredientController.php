<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientGroup;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
     /**
     * Display all ingredients
     */
    public function index(Request $request)
    {
        $query = Ingredient::with('groups');

        // Filter by group
        if ($request->filled('group')) {
            $query->whereHas('groups', function ($q) use ($request) {
                $q->where('ingredient_groups.id', $request->group);
            });
        }

        // Filter low stock only
        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        $ingredients = $query->orderBy('name')->paginate(15);
        $ingredientGroups = IngredientGroup::withCount('ingredients')->get();

        // Stats
        $stats = [
            'total' => Ingredient::count(),
            'low_stock' => Ingredient::lowStock()->count(),
            'groups' => IngredientGroup::count(),
        ];

        return view('ingredients.index', compact('ingredients', 'ingredientGroups', 'stats'));
    }

    /**
     * Show form for creating new ingredient
     */
    public function create()
    {
        $groups = IngredientGroup::all();
        return view('ingredients.create', compact('groups'));
    }

    /**
     * Store a new ingredient
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'initial_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'low_stock_threshold' => 'required|numeric|min:0|max:100',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:ingredient_groups,id',
        ]);

        // Set current_stock same as initial_stock
        $validated['current_stock'] = $validated['initial_stock'];

        $ingredient = Ingredient::create($validated);

        // Attach to groups
        if ($request->has('groups')) {
            $ingredient->groups()->attach($request->groups);
        }

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient created successfully!');
    }

    /**
     * Display single ingredient
     */
    public function show(Ingredient $ingredient)
    {
        $ingredient->load(['groups', 'menuItems']);
        return view('ingredients.show', compact('ingredient'));
    }

    /**
     * Show form for editing ingredient
     */
    public function edit(Ingredient $ingredient)
    {
        $groups = IngredientGroup::all();
        $ingredient->load('groups');
        
        return view('ingredients.edit', compact('ingredient', 'groups'));
    }

    /**
     * Update an ingredient
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'low_stock_threshold' => 'required|numeric|min:0|max:100',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:ingredient_groups,id',
        ]);

        // Update initial_stock if current exceeds it (restocking)
        if ($validated['current_stock'] > $ingredient->initial_stock) {
            $validated['initial_stock'] = $validated['current_stock'];
        }

        $ingredient->update($validated);

        // Sync groups
        $ingredient->groups()->sync($request->groups ?? []);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient updated successfully!');
    }

    /**
     * Delete an ingredient
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient deleted successfully!');
    }

    /**
     * Restock an ingredient (quick action)
     */
    public function restock(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $ingredient->addStock($validated['amount']);

        return back()->with('success', "Added {$validated['amount']} {$ingredient->unit} to {$ingredient->name}");
    }

    /**
     * Display all ingredient groups
     */
    public function groups()
    {
        $groups = IngredientGroup::withCount('ingredients')->get();
        return view('ingredients.groups', compact('groups'));
    }
}
