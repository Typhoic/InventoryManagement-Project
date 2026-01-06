<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item - Cal's Chicken Bowl</title>

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
            <h1>Add New Item</h1>
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

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
                @csrf

                <!-- Item Type Selection -->
                <div class="form-group">
                    <label class="form-label">Item Type *</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="item_type" value="product" {{ old('item_type', 'product') == 'product' ? 'checked' : '' }} onchange="toggleFormFields()">
                            <span class="radio-custom"></span>
                            <span class="radio-label">Product (Menu Item)</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="item_type" value="ingredient" {{ old('item_type') == 'ingredient' ? 'checked' : '' }} onchange="toggleFormFields()">
                            <span class="radio-custom"></span>
                            <span class="radio-label">Ingredient</span>
                        </label>
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="form-group">
                    <label class="form-label" for="name">Name *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" placeholder="Enter item name" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea" placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                </div>

                <!-- Product Fields -->
                <div id="product-fields">
                    <div class="form-group">
                        <label class="form-label" for="price">Selling Price (Rp) *</label>
                        <input type="number" name="price" id="price" class="form-input" value="{{ old('price') }}" placeholder="e.g. 25000" min="0">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Product Image</label>
                        <input type="file" name="image" id="image" class="form-input-file" accept="image/*">
                        <span class="form-hint">Accepted formats: JPG, PNG. Max size: 2MB</span>
                    </div>

                    <!-- Recipe Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">Recipe Ingredients</h3>
                        <p class="form-section-hint">Define what ingredients are needed to make this product</p>
                        
                        <div id="recipe-container">
                            <!-- Recipe rows will be added here -->
                        </div>
                        
                        <button type="button" class="add-recipe-btn" onclick="addRecipeRow()">+ Add Ingredient</button>
                    </div>
                </div>

                <!-- Ingredient Fields -->
                <div id="ingredient-fields" style="display: none;">
                    <div class="form-row">
                        <div class="form-group half">
                            <label class="form-label" for="initial_stock">Initial Stock *</label>
                            <input type="number" name="initial_stock" id="initial_stock" class="form-input" value="{{ old('initial_stock') }}" placeholder="e.g. 5000" min="0" step="0.01">
                        </div>
                        <div class="form-group half">
                            <label class="form-label" for="unit">Unit *</label>
                            <select name="unit" id="unit" class="form-select">
                                <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="low_stock_threshold">Low Stock Threshold (%)</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="form-input" value="{{ old('low_stock_threshold', 30) }}" placeholder="e.g. 30" min="0" max="100">
                        <span class="form-hint">Alert when stock falls below this percentage</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="ingredient_group">Ingredient Group</label>
                        <select name="ingredient_group[]" id="ingredient_group" class="form-select" multiple>
                            @foreach($ingredientGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <span class="form-hint">Hold Ctrl/Cmd to select multiple groups</span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('itemdetailsclicked') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">Save Item</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Available ingredients for recipe (passed from controller)
        const availableIngredients = @json($ingredients ?? []);
        let recipeRowCount = 0;

        function toggleFormFields() {
            const itemType = document.querySelector('input[name="item_type"]:checked').value;
            const productFields = document.getElementById('product-fields');
            const ingredientFields = document.getElementById('ingredient-fields');

            if (itemType === 'product') {
                productFields.style.display = 'block';
                ingredientFields.style.display = 'none';
            } else {
                productFields.style.display = 'none';
                ingredientFields.style.display = 'block';
            }
        }

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
            if (row) {
                row.remove();
            }
        }

        // Initialize
        toggleFormFields();
    </script>
</body>
</html>