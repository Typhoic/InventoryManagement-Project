<?php

namespace App\Http\Controllers;


use App\Models\MenuItem;
use App\Models\Ingredient;
use App\Models\IngredientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
        /**
     * Display all items (products + ingredients)
     */
    public function allItems(Request $request)
    {
        // Get filter parameters
        $type = $request->get('type');
        $sort = $request->get('sort');
    
        $items = collect();
    
        // Get menu items (products)
        if (!$type || $type == 'product') {
            $menuItems = MenuItem::all()->map(function ($item) {
                return (object) [
                    'id' => 'menu_' . $item->id,
                    'real_id' => $item->id,
                    'name' => $item->name,
                    'type' => 'product',
                    'selling_price' => $item->price,
                    'description' => $item->description,
                    'image_url' => $item->image_url,
                    'model' => 'MenuItem'
                ];
            });
            $items = $items->concat($menuItems);
        }
    
        // Get ingredients
        if (!$type || $type == 'ingredient') {
            $ingredients = Ingredient::all()->map(function ($item) {
                return (object) [
                    'id' => 'ing_' . $item->id,
                    'real_id' => $item->id,
                    'name' => $item->name,
                    'type' => 'ingredient',
                    'selling_price' => null,
                    'description' => 'Stock: ' . $item->current_stock . ' ' . $item->unit,
                    'image_url' => null,
                    'model' => 'Ingredient'
                ];
            });
            $items = $items->concat($ingredients);
        }
    
        // Sort items
        if ($sort) {
            switch ($sort) {
                case 'name_asc':
                    $items = $items->sortBy('name')->values();
                    break;
                case 'name_desc':
                    $items = $items->sortByDesc('name')->values();
                    break;
            }
        }
    
        return view('itemdetailsclicked', compact('items'));
    }

    /**
     * Show create form for items (product or ingredient)
     */
    public function createItem()
    {
        $ingredients = Ingredient::all();
        $ingredientGroups = IngredientGroup::all();
        
        return view('items.create', compact('ingredients', 'ingredientGroups'));
    }

    /**
     * Store a new item (product or ingredient)
     */
    public function storeItem(Request $request)
    {
        $itemType = $request->input('item_type');

        if ($itemType === 'product') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'ingredients' => 'nullable|array',
            ]);

            $imageUrl = null;
            if ($request->hasFile('image')) {
                $imageUrl = $request->file('image')->store('menu-items', 'public');
            }

            $menuItem = MenuItem::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'image_url' => $imageUrl,
                'is_active' => true,
            ]);

            // Attach ingredients (recipe)
            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $ingredient) {
                    if (!empty($ingredient['id']) && !empty($ingredient['quantity_used'])) {
                        $menuItem->ingredients()->attach($ingredient['id'], [
                            'quantity_used' => $ingredient['quantity_used']
                        ]);
                    }
                }
            }

            return redirect()->route('itemdetailsclicked')->with('success', 'Product created successfully!');

        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'initial_stock' => 'required|numeric|min:0',
                'unit' => 'required|string',
                'low_stock_threshold' => 'nullable|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'ingredient_group' => 'nullable|array',
            ]);

            $ingredient = Ingredient::create([
                'name' => $validated['name'],
                'initial_stock' => $validated['initial_stock'],
                'current_stock' => $validated['initial_stock'],
                'unit' => $validated['unit'],
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? 30,
            ]);

            // Attach to groups
            if ($request->has('ingredient_group')) {
                $ingredient->groups()->attach($request->ingredient_group);
            }

            return redirect()->route('itemdetailsclicked')->with('success', 'Ingredient created successfully!');
        }
    }

    /**
     * Show edit form for item
     */
    public function editItem($id)
    {
        if (str_starts_with($id, 'menu_')) {
            $realId = str_replace('menu_', '', $id);
            $menuItem = MenuItem::with('ingredients')->findOrFail($realId);
            
            $item = (object) [
                'id' => $id,
                'real_id' => $menuItem->id,
                'name' => $menuItem->name,
                'description' => $menuItem->description,
                'selling_price' => $menuItem->price,
                'image_url' => $menuItem->image_url,
                'model' => 'MenuItem'
            ];
            
            $ingredients = Ingredient::all();
            
            return view('items.edit', compact('item', 'menuItem', 'ingredients'));
            
        } else {
            $realId = str_replace('ing_', '', $id);
            $ingredientItem = Ingredient::with('groups')->findOrFail($realId);
            
            $item = (object) [
                'id' => $id,
                'real_id' => $ingredientItem->id,
                'name' => $ingredientItem->name,
                'description' => 'Stock: ' . $ingredientItem->current_stock . ' ' . $ingredientItem->unit,
                'model' => 'Ingredient'
            ];
            
            $ingredientGroups = IngredientGroup::all();
            
            return view('items.edit', compact('item', 'ingredientItem', 'ingredientGroups'));
        }
    }

    /**
     * Update item
     */
    public function updateItem(Request $request, $id)
    {
        $itemType = $request->input('item_type');

        if ($itemType === 'product') {
            $menuItem = MenuItem::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'ingredients' => 'nullable|array',
            ]);

            if ($request->hasFile('image')) {
                if ($menuItem->image_url) {
                    Storage::disk('public')->delete($menuItem->image_url);
                }
                $menuItem->image_url = $request->file('image')->store('menu-items', 'public');
            }

            $menuItem->name = $validated['name'];
            $menuItem->price = $validated['price'];
            $menuItem->description = $validated['description'] ?? null;
            $menuItem->save();

            // Sync ingredients
            $ingredientsData = [];
            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $ingredient) {
                    if (!empty($ingredient['id']) && !empty($ingredient['quantity_used'])) {
                        $ingredientsData[$ingredient['id']] = ['quantity_used' => $ingredient['quantity_used']];
                    }
                }
            }
            $menuItem->ingredients()->sync($ingredientsData);

            return redirect()->route('itemdetailsclicked')->with('success', 'Product updated successfully!');

        } else {
            $ingredient = Ingredient::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'current_stock' => 'required|numeric|min:0',
                'unit' => 'required|string',
                'low_stock_threshold' => 'nullable|numeric|min:0|max:100',
            ]);

            if ($validated['current_stock'] > $ingredient->initial_stock) {
                $ingredient->initial_stock = $validated['current_stock'];
            }

            $ingredient->name = $validated['name'];
            $ingredient->current_stock = $validated['current_stock'];
            $ingredient->unit = $validated['unit'];
            $ingredient->low_stock_threshold = $validated['low_stock_threshold'] ?? 30;
            $ingredient->save();

            return redirect()->route('itemdetailsclicked')->with('success', 'Ingredient updated successfully!');
        }
    }

    /**
     * Delete item
     */
    public function destroyItem($id)
    {
        if (str_starts_with($id, 'menu_')) {
            $realId = str_replace('menu_', '', $id);
            $menuItem = MenuItem::findOrFail($realId);
            
            if ($menuItem->image_url) {
                Storage::disk('public')->delete($menuItem->image_url);
            }
            $menuItem->delete();
            
        } else {
            $realId = str_replace('ing_', '', $id);
            Ingredient::findOrFail($realId)->delete();
        }

        return redirect()->route('itemdetailsclicked')->with('success', 'Item deleted successfully!');
    }

    /**
     * Display low stock items
     */
    public function lowStockItems()
    {
        $items = Ingredient::lowStock()->get();

        return view('lowstockitems', compact('items'));
    }
}
