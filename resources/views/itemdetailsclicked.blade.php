<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Items - Cal's Chicken Bowl</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/item_details_clicked.css') }}">
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
            <h1>All Items</h1>
            <div class="page-actions">

                <!-- Filter Dropdown -->
                <div class="filter-wrapper">
                    <button class="filter-btn-page" onclick="toggleFilterDropdown()">
                        Filter
                        @if(request('type') || request('sort'))
                            <span class="filter-badge">●</span>
                        @endif
                        <span class="filter-arrow">▼</span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="filter-dropdown" id="filterDropdown">
                        <form action="{{ route('itemdetailsclicked') }}" method="GET" id="filterForm">
                            <!-- Type Filter -->
                            <div class="filter-section">
                                <span class="filter-section-title">Type</span>
                                <label class="filter-option">
                                    <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }}>
                                    <span>All Items</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="type" value="product" {{ request('type') == 'product' ? 'checked' : '' }}>
                                    <span>Products</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="type" value="ingredient" {{ request('type') == 'ingredient' ? 'checked' : '' }}>
                                    <span>Ingredients</span>
                                </label>
                            </div>

                            <!-- Sort Filter -->
                            <div class="filter-section">
                                <span class="filter-section-title">Sort By</span>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="" {{ !request('sort') ? 'checked' : '' }}>
                                    <span>Default</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="name_asc" {{ request('sort') == 'name_asc' ? 'checked' : '' }}>
                                    <span>Name: A to Z</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="name_desc" {{ request('sort') == 'name_desc' ? 'checked' : '' }}>
                                    <span>Name: Z to A</span>
                                </label>
                            </div>

                            <!-- Filter Actions -->
                            <div class="filter-dropdown-actions">
                                <a href="{{ route('itemdetailsclicked') }}" class="filter-reset-btn">Reset</a>
                                <button type="submit" class="filter-apply-btn">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                

                <a href="{{ route('items.create') }}" class="new-btn">+ New</a>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-container">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Selling Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td>
                                <div class="item-name-cell">
                                    @if($item->image_url)
                                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="item-thumbnail">
                                    @else
                                        <div class="item-thumbnail-placeholder">N/A</div>
                                    @endif
                                    <span>{{ $item->name }}</span>
                                </div>
                            </td>
                            <td>{{ $item->type == 'product' ? 'Product' : 'Ingredients' }}</td>
                            <td>
                                @if($item->type == 'product')
                                    Rp{{ number_format($item->selling_price, 0, ',', '.') }},-
                                @else
                                    -
                                @endif
                            </td>
                            <td class="description-cell">{{ Str::limit($item->description, 30) }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('items.edit', $item->id) }}" class="edit-btn">Edit</a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">No items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </main>

    <script>
        function toggleFilterDropdown() {
            const dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const wrapper = document.querySelector('.filter-wrapper');
            const dropdown = document.getElementById('filterDropdown');
            
            if (wrapper && dropdown && !wrapper.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>