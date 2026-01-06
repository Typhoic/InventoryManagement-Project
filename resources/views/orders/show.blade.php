<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }} - Cal's Chicken Bowl</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manjari:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/order_show.css') }}">
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
            <h1>Order #{{ $order->id }}</h1>
            <div class="page-actions">
                <a href="{{ route('salesorderclicked') }}" class="back-btn">← Back to Orders</a>
            </div>
        </div>

        <!-- Order Details -->
        <div class="order-details-container">
            <!-- Order Info -->
            <div class="order-info-card">
                <h2>Order Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Order ID</span>
                        <span class="info-value">#{{ $order->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Channel</span>
                        <span class="info-value">
                            <span class="channel-badge channel-{{ $order->channel }}">
                                {{ $order->getChannelLabel() }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ $order->getStatusLabel() }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="order-items-card">
                <h2>Order Items</h2>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->menuItem->name ?? 'N/A' }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }},-</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }},-</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="total-label">Total</td>
                            <td class="total-value">Rp{{ number_format($order->total_amount, 0, ',', '.') }},-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Actions -->
            <div class="order-actions">
                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Delete Order</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>