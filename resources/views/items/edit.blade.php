<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - Cal's Chicken Bowl</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/sidebar.css') }}">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="leftsection">
            <img class="logoheader logo-clickable" src="{{ asset('images/ccb_logo_notext.svg') }}" onclick="openSidebar()">
        </div>
    </header>

    <!-- Include Sidebar -->
    @include('partials.sidebar')
    
    <main>
        <!-- Page Title Section -->
        <div class="page-title-section">
            <h1>Edit Item: {{ $item->name }}</h1>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('items.update', $item->real_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="item_type" value="{{ $item->model == 'MenuItem' ? 'product' : 'ingredient' }}">

                <!-- Item Type Display (Read-only) -->
                <div class="form-group">
                    <label class="form-label">Item Type</label>
                    <div class="type-badge {{ $item->model == 'MenuItem' ? 'type-product' : 'type-ingredient' }}">
                        {{ $item->model == 'MenuItem' ? 'Product (Menu Item)' : 'Ingredient' }}
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="form-group">
                    <label class="form-label" for="name">Name *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $item->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea">{{ old('description', $item->description) }}</textarea>
                </div>

                @if($item->model == 'MenuItem')
                    <!-- Product Fields -->
                    <div class="form-group">
                        <label class="form-label" for="price">Selling Price (Rp) *</label>
                        <input type="number" name="price" id="price" class="form-input" value="{{ old('price', $item->selling_price) }}" min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Product Image</label>
                        @if($item->image_url)
                            <div class="current-image">
                                <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                                <span>Current image</span>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-input-file" accept="image/*">
                        <span class="form-hint">Leave empty to keep current image</span>
                    </div>

                    <!-- Recipe Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">Recipe Ingredients</h3>
                        
                        <div id="recipe-container">
                            @foreach($menuItem->ingredients as $index => $ingredient)
                                <div class="recipe-row" id="recipe-row-{{ $index }}">
                                    <div class="recipe-select">
                                        <select name="ingredients[{{ $index }}][id]" class="form-select" required>
                                            <option value="">Select Ingredient</option>
                                            @foreach($ingredients as $ing)
                                                <option value="{{ $ing->id }}" {{ $ingredient->id == $ing->id ? 'selected' : '' }}>
                                                    {{ $ing->name }} ({{ $ing->current_stock }} {{ $ing->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="recipe-quantity">
                                        <input type="number" name="ingredients[{{ $index }}][quantity_used]" class="form-input" value="{{ $ingredient->pivot->quantity_used }}" min="0" step="0.01" required>
                                    </div>
                                    <div class="recipe-unit">
                                        <span class="unit-label">{{ $ingredient->unit }}</span>
                                    </div>
                                    <button type="button" class="recipe-remove-btn" onclick="removeRecipeRow({{ $index }})">×</button>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="add-recipe-btn" onclick="addRecipeRow()">+ Add Ingredient</button>
                    </div>
                @else
                    <!-- Ingredient Fields -->
                    <div class="form-row">
                        <div class="form-group half">
                            <label class="form-label" for="current_stock">Current Stock *</label>
                            <input type="number" name="current_stock" id="current_stock" class="form-input" value="{{ old('current_stock', $ingredientItem->current_stock) }}" min="0" step="0.01" required>
                        </div>
                        <div class="form-group half">
                            <label class="form-label" for="unit">Unit *</label>
                            <select name="unit" id="unit" class="form-select" required>
                                <option value="gram" {{ $ingredientItem->unit == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                <option value="kg" {{ $ingredientItem->unit == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="ml" {{ $ingredientItem->unit == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                <option value="liter" {{ $ingredientItem->unit == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="pcs" {{ $ingredientItem->unit == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="low_stock_threshold">Low Stock Threshold (%)</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="form-input" value="{{ old('low_stock_threshold', $ingredientItem->low_stock_threshold) }}" min="0" max="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock Info</label>
                        <div class="stock-info">
                            <span>Initial Stock: {{ $ingredientItem->initial_stock }} {{ $ingredientItem->unit }}</span>
                            <span>Current: {{ $ingredientItem->getStockPercentage() }}% remaining</span>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('itemdetailsclicked') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">Update Item</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const availableIngredients = @json($ingredients ?? []);
        let recipeRowCount = {{ isset($menuItem) ? $menuItem->ingredients->count() : 0 }};

        function addRecipeRow() {
            const container = document.getElementById('recipe-container');
            const row = document.createElement('div');
            row.className = 'recipe-row';
            row.id = `recipe-row-${recipeRowCount}`;

            let optionsHtml = '<option value="">Select Ingredient</option>';
            availableIngredients.forEach(ing => {
                optionsHtml += `<option value="${ing.id}">${ing.name} (${ing.current_stock} ${ing.unit})</option>`;
            });

            row.innerHTML = `
                <div class="recipe-select">
                    <select name="ingredients[${recipeRowCount}][id]" class="form-select" required>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="recipe-quantity">
                    <input type="number" name="ingredients[${recipeRowCount}][quantity_used]" class="form-input" placeholder="Qty" min="0" step="0.01" required>
                </div>
                <div class="recipe-unit">
                    <span class="unit-label">gram</span>
                </div>
                <button type="button" class="recipe-remove-btn" onclick="removeRecipeRow(${recipeRowCount})">×</button>
            `;

            container.appendChild(row);
            recipeRowCount++;
        }

        function removeRecipeRow(id) {
            const row = document.getElementById(`recipe-row-${id}`);
            if (row) row.remove();
        }
    </script>
</body>
</html>