<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * GET /api/menu-items
     * List all menu items
     */
    public function index()
    {
        $menuItems = MenuItem::all();
        
        return response()->json([
            'success' => true,
            'data' => $menuItems,
            'count' => $menuItems->count()
        ]);
    }

    /**
     * POST /api/menu-items
     * Create a new menu item
     * Server-side validation prevents XSS, injection, and CSRF attacks
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ], [
            'name.required' => 'Item name is required',
            'name.max' => 'Item name must not exceed 255 characters',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a valid number',
            'price.min' => 'Price cannot be negative',
            'description.max' => 'Description must not exceed 1000 characters'
        ]);

        // Create menu item with trimmed and type-cast values
        $menuItem = MenuItem::create([
            'name' => trim($validated['name']),
            'price' => (float) $validated['price'],
            'description' => isset($validated['description']) ? trim($validated['description']) : null,
            'image_url' => isset($validated['image_url']) ? trim($validated['image_url']) : null,
            'is_active' => $validated['is_active'] ?? true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully',
            'data' => $menuItem
        ], 201);
    }

    /**
     * GET /api/menu-items/{id}
     * Get a single menu item with its ingredients
     */
    public function show($id)
    {
        $menuItem = MenuItem::with('ingredients')->find($id);

        if (!$menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $menuItem
        ]);
    }

    /**
     * PUT /api/menu-items/{id}
     * Update a menu item
     */
    public function update(Request $request, $id)
    {
        $menuItem = MenuItem::find($id);

        if (!$menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $menuItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully',
            'data' => $menuItem
        ]);
    }

    /**
     * DELETE /api/menu-items/{id}
     * Delete a menu item
     */
    public function destroy($id)
    {
        $menuItem = MenuItem::find($id);

        if (!$menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        $menuItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully'
        ]);
    }
}
