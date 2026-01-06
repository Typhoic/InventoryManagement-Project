<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Items - Cal's Chicken Bowl</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/item_details_clicked.css') }}">
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
            <h1>Low Stock Items</h1>
            <div class="page-actions">
                <span class="low-stock-warning">⚠️ {{ $items->count() }} item(s) need restocking</span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-container">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Current Stock</th>
                        <th>Initial Stock</th>
                        <th>Stock Level</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td>
                                <div class="item-name-cell">                                    
                                    <span>{{ $item->name }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($item->current_stock, 0, ',', '.') }} {{ $item->unit }}</td>
                            <td>{{ number_format($item->initial_stock, 0, ',', '.') }} {{ $item->unit }}</td>
                            <td>
                                <div class="stock-level-bar">
                                    <div class="stock-level-fill" style="width: {{ $item->getStockPercentage() }}%;"></div>
                                </div>
                                <span class="stock-percentage">{{ number_format($item->getStockPercentage(), 1) }}%</span>
                            </td>
                            <td>
                                <span class="status-badge status-low">Low Stock</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">
                                <div class="no-low-stock">
                                    <span class="no-low-stock-icon">✅</span>
                                    <p>All items are well stocked!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </main>
</body>
</html>