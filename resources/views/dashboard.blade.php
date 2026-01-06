<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cal's Chicken Bowl - Stock Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/welcome_section.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/sales_order.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/item_details.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/top_selling_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/sales_summary.css') }}">
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
        <!-- Welcome admin Section -->
        <div class="welcome_admin">
            <h1>Hello, Admin</h1>
            <p>Cal's Chicken Bowl Owner</p>
        </div>

        <!-- Dashboard Container -->
        <div class="dashboard-container">
            <!-- Left Column -->
            <div class="dashboard-left-column">
                <!-- Sales order Section -->
                <table class="salesorder">
                    <tr>
                        <th colspan="5" style="position: relative;">
                            <a href="{{ route('salesorderclicked') }}" class="section-title-link">Sales Order</a>
                            <div class="filter-buttons">
                                <a href="{{ route('dashboard', ['filter' => 'today']) }}" 
                                   class="filter-btn {{ $filter == 'today' ? 'active' : '' }}">Today</a>
                                <a href="{{ route('dashboard', ['filter' => 'week']) }}" 
                                   class="filter-btn {{ $filter == 'week' ? 'active' : '' }}">This Week</a>
                                <a href="{{ route('dashboard', ['filter' => 'month']) }}" 
                                   class="filter-btn {{ $filter == 'month' ? 'active' : '' }}">This Month</a>
                                <a href="{{ route('dashboard', ['filter' => 'year']) }}" 
                                   class="filter-btn {{ $filter == 'year' ? 'active' : '' }}">This Year</a>
                            </div>
                        </th>
                    </tr>

                    <tr>
                        <td>Total Order</td>
                        <td>Dine In</td>
                        <td>Catering</td>
                        <td>Go Food</td>
                        <td>Grab Food</td>
                    </tr>

                    <tr>
                        <td>{{ $totalOrders }}</td>
                        <td>{{ $dineIn }}</td>
                        <td>{{ $catering }}</td>
                        <td>{{ $goFood }}</td>
                        <td>{{ $grabFood }}</td>
                    </tr>
                </table>

                <!-- Item Details Section -->
                <div class="item-details-container">
                    <div class="item-details-header">
                        <h2>Item Details</h2>
                    </div>
                    
                    <div class="item-details-content">
                        <!-- Left Section -->
                        <div class="item-stats">
                            <div class="stat-row low-stock">
                                <a href="{{ route('lowstockitems') }}" class="stat-label-link-lowstock">Low Stock Items</a>
                                <span class="stat-number">{{ $lowStockItems }}</span>
                            </div>
                            
                            <div class="stat-row">
                                <span class="stat-label">All Item Groups</span>
                                <span class="stat-number">{{ $itemGroups }}</span>
                            </div>
                            
                            <div class="stat-row">
                                <a href="{{ route('itemdetailsclicked') }}" class="stat-label-link">All Items</a>
                                <span class="stat-number">{{ $allItems }}</span>
                            </div>
                        </div>
                        
                        <!-- Right Section -->
                        <div class="active-items-chart">
                            <h3>Active Items</h3>
                            <div class="chart-container">
                                @php
                                    $circumference = 2 * 3.14159 * 50; // 314.16
                                    $activeArc = ($activeItemsPercentage / 100) * $circumference;
                                    $inactiveArc = $circumference - $activeArc;
                                    $activeRotation = -90 + (($inactiveArc / $circumference) * 360);
                                @endphp
                                <svg class="progress-ring" viewBox="0 0 120 120">
                                    <circle
                                        class="progress-ring-bg"
                                        cx="60"
                                        cy="60"
                                        r="50"
                                        fill="none"
                                        stroke="#e0e0e0"
                                        stroke-width="20"
                                    />
                                    
                                    <circle
                                        class="progress-ring-inactive"
                                        cx="60"
                                        cy="60"
                                        r="50"
                                        fill="none"
                                        stroke="#FF6B6B"
                                        stroke-width="20"
                                        stroke-dasharray="{{ $inactiveArc }} {{ $circumference }}"
                                        stroke-dashoffset="0"
                                        transform="rotate(-90 60 60)"
                                    />
                                    
                                    <circle
                                        class="progress-ring-active"
                                        cx="60"
                                        cy="60"
                                        r="50"
                                        fill="none"
                                        stroke="#5FD4A0"
                                        stroke-width="20"
                                        stroke-dasharray="{{ $activeArc }} {{ $circumference }}"
                                        stroke-dashoffset="0"
                                        transform="rotate({{ $activeRotation }} 60 60)"
                                    />
                                </svg>
                                <div class="chart-percentage">{{ $activeItemsPercentage }}%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Summary Section -->
                <div class="sales-summary-container">
                    <div class="sales-summary-header">
                        <h2>Sales Summary
                            <div class="filter-buttons-ss">
                                <a href="{{ route('dashboard', ['filter' => 'today']) }}" 
                                class="filter-btn-ss {{ $filter == 'today' ? 'active' : '' }}">Today</a>
                                <a href="{{ route('dashboard', ['filter' => 'week']) }}" 
                                class="filter-btn-ss {{ $filter == 'week' ? 'active' : '' }}">This Week</a>
                                <a href="{{ route('dashboard', ['filter' => 'month']) }}" 
                                class="filter-btn-ss {{ $filter == 'month' ? 'active' : '' }}">This Month</a>
                                <a href="{{ route('dashboard', ['filter' => 'year']) }}" 
                                class="filter-btn-ss {{ $filter == 'year' ? 'active' : '' }}">This Year</a>
                            </div>
                        </h2>
                    </div>
                    
                    <div class="sales-summary-content">
                        <div class="chart-wrapper">
                            <canvas id="salesChart"></canvas>
                        </div>
                        <div class="total-sales-box">
                            <span class="total-label">Total Sales</span>
                            <span class="total-value">Rp {{ number_format($totalSales, 0, ',', '.') }},-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="dashboard-right-column">
                <!-- Top Selling Menu Section -->
                <div class="top-selling-container">
                    <div class="top-selling-header">
                        <h2 style="position: relative;">
                            Top Selling Menu
                            <div class="filter-buttons-tsm">
                                <a href="{{ route('dashboard', ['filter' => 'today']) }}" 
                                   class="filter-btn-tsm {{ $filter == 'today' ? 'active' : '' }}">Today</a>
                                <a href="{{ route('dashboard', ['filter' => 'week']) }}" 
                                   class="filter-btn-tsm {{ $filter == 'week' ? 'active' : '' }}">This Week</a>
                                <a href="{{ route('dashboard', ['filter' => 'month']) }}" 
                                   class="filter-btn-tsm {{ $filter == 'month' ? 'active' : '' }}">This Month</a>
                                <a href="{{ route('dashboard', ['filter' => 'year']) }}" 
                                   class="filter-btn-tsm {{ $filter == 'year' ? 'active' : '' }}">This Year</a>
                            </div>
                        </h2>
                    </div>
                    
                    <div class="top-selling-content">
                        @if($topSelling->count() > 0)
                            <!-- First Row - First 3 items -->
                            <div class="menu-row">
                                @foreach($topSelling->take(3) as $menu)
                                    <div class="menu-card">
                                        @if($menu->image_url)
                                            <img src="{{ asset('storage/' . $menu->image_url) }}" alt="{{ $menu->name }}" class="menu-image">
                                        @else
                                            <div class="menu-image-placeholder">🍗</div>
                                        @endif
                                        <p class="menu-name">{{ $menu->name }}</p>
                                        <p class="menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }},-</p>
                                    </div>
                                @endforeach

                                {{-- Fill empty slots if less than 3 items --}}
                                @for($i = $topSelling->take(3)->count(); $i < 3; $i++)
                                    <div class="menu-card empty-card"></div>
                                @endfor
                            </div>
                            
                            <!-- Second Row - Next 2 items + See All button -->
                            <div class="menu-row">
                                @foreach($topSelling->slice(3, 2) as $menu)
                                    <div class="menu-card">
                                        @if($menu->image_url)
                                            <img src="{{ asset('storage/' . $menu->image_url) }}" alt="{{ $menu->name }}" class="menu-image">
                                        @else
                                            <div class="menu-image-placeholder">🍗</div>
                                        @endif
                                        <p class="menu-name">{{ $menu->name }}</p>
                                        <p class="menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }},-</p>
                                    </div>
                                @endforeach

                                {{-- Fill empty slots if less than 2 items in second row --}}
                                @for($i = $topSelling->slice(3, 2)->count(); $i < 2; $i++)
                                    <div class="menu-card empty-card"></div>
                                @endfor
                                
                                
                            </div>
                        @else
                            <div class="no-data">
                                <p>No sales data yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js Script -->
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = @json($salesChartData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.labels.length > 0 ? salesData.labels : ['No Data'],
                datasets: [{
                    label: 'Sales (Rp)',
                    data: salesData.values.length > 0 ? salesData.values : [0],
                    borderColor: '#3498DB',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#3498DB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000) + 'K';
                            }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>