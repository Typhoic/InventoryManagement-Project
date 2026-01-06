<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Sales Order - Cal's Chicken Bowl</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/sales_order_clicked.css') }}">
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
            <h1>All Sales Order</h1>
            <div class="page-actions">
                <!-- Filter Dropdown -->
                <div class="filter-wrapper">
                    <button class="filter-btn-page" onclick="toggleFilterDropdown()">
                        Filter
                        @if(request('channel') || request('sort'))
                            <span class="filter-badge">●</span>
                        @endif
                        <span class="filter-arrow">▼</span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="filter-dropdown" id="filterDropdown">
                        <form action="{{ route('salesorderclicked') }}" method="GET" id="filterForm">
                            <!-- Channel Filter -->
                            <div class="filter-section">
                                <span class="filter-section-title">Channel</span>
                                <label class="filter-option">
                                    <input type="radio" name="channel" value="" {{ !request('channel') ? 'checked' : '' }}>
                                    <span>All Channels</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="channel" value="dine_in" {{ request('channel') == 'dine_in' ? 'checked' : '' }}>
                                    <span>Dine In</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="channel" value="catering" {{ request('channel') == 'catering' ? 'checked' : '' }}>
                                    <span>Catering</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="channel" value="go_food" {{ request('channel') == 'go_food' ? 'checked' : '' }}>
                                    <span>Go Food</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="channel" value="grab_food" {{ request('channel') == 'grab_food' ? 'checked' : '' }}>
                                    <span>Grab Food</span>
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
                                    <input type="radio" name="sort" value="date_newest" {{ request('sort') == 'date_newest' ? 'checked' : '' }}>
                                    <span>Date: Newest First</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="date_oldest" {{ request('sort') == 'date_oldest' ? 'checked' : '' }}>
                                    <span>Date: Oldest First</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="price_low" {{ request('sort') == 'price_low' ? 'checked' : '' }}>
                                    <span>Price: Low to High</span>
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="price_high" {{ request('sort') == 'price_high' ? 'checked' : '' }}>
                                    <span>Price: High to Low</span>
                                </label>
                            </div>

                            <!-- Filter Actions -->
                            <div class="filter-dropdown-actions">
                                <a href="{{ route('salesorderclicked') }}" class="filter-reset-btn">Reset</a>
                                <button type="submit" class="filter-apply-btn">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>

                <a href="{{ route('orders.create') }}" class="new-btn">+ New</a>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Total Price</th>
                        <th>OrderID</th>
                        <th>Channel</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>
                                @foreach($order->orderItems as $itemIndex => $orderItem)
                                    {{ $orderItem->menuItem->name ?? 'N/A' }}@if($itemIndex < $order->orderItems->count() - 1), @endif
                                @endforeach
                            </td>
                            <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }},-</td>
                            <td>{{ $order->id }}</td>
                            <td>
                                <span class="channel-badge channel-{{ $order->channel }}">
                                    {{ $order->getChannelLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('orders.show', $order->id) }}" class="view-btn">View</a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-data">No orders found</td>
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
            
            if (!wrapper.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>