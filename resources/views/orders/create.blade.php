<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Sales Order - Cal's Chicken Bowl</title>

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
            <h1>New Sales Order</h1>
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

            <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                @csrf

                <!-- Channel Selection -->
                <div class="form-group">
                    <label class="form-label">Order Channel *</label>
                    <div class="channel-options">
                        @foreach($channels as $key => $label)
                            <label class="channel-option">
                                <input type="radio" name="channel" value="{{ $key }}" {{ old('channel') == $key ? 'checked' : '' }} required>
                                <span class="channel-box">
                                    <span class="channel-icon">
                                        @if($key == 'dine_in') 
                                        @elseif($key == 'go_food') 
                                        @elseif($key == 'grab_food') 
                                        @else 
                                        @endif
                                    </span>
                                    <span class="channel-name">{{ $label }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Order Items -->
                <div class="form-section">
                    <h3 class="form-section-title">Order Items</h3>
                    
                    <div id="order-items-container">
                        <!-- Order item rows will be added here -->
                    </div>
                    
                    <button type="button" class="add-item-btn" onclick="addOrderItem()">+ Add Menu Item</button>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value" id="subtotal">Rp 0,-</span>
                    </div>
                    <div class="summary-row total">
                        <span class="summary-label">Total:</span>
                        <span class="summary-value" id="total">Rp 0,-</span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('salesorderclicked') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">Create Order</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const menuItems = @json($menuItems);
        let orderItemCount = 0;

        function addOrderItem() {
            const container = document.getElementById('order-items-container');
            const row = document.createElement('div');
            row.className = 'order-item-row';
            row.id = `order-item-${orderItemCount}`;

            let optionsHtml = '<option value="">Select Menu Item</option>';
            menuItems.forEach(item => {
                optionsHtml += `<option value="${item.id}" data-price="${item.price}">${item.name} - Rp ${formatNumber(item.price)},-</option>`;
            });

            row.innerHTML = `
                <div class="order-item-select">
                    <select name="items[${orderItemCount}][menu_item_id]" class="form-select" onchange="updateOrderSummary()" required>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="order-item-quantity">
                    <label>Qty:</label>
                    <input type="number" name="items[${orderItemCount}][quantity]" class="form-input quantity-input" value="1" min="1" onchange="updateOrderSummary()" required>
                </div>
                <div class="order-item-price">
                    <span class="item-price" id="item-price-${orderItemCount}">Rp 0,-</span>
                </div>
                <button type="button" class="order-item-remove" onclick="removeOrderItem(${orderItemCount})">×</button>
            `;

            container.appendChild(row);
            orderItemCount++;
        }

        function removeOrderItem(id) {
            const row = document.getElementById(`order-item-${id}`);
            if (row) {
                row.remove();
                updateOrderSummary();
            }
        }

        function updateOrderSummary() {
            let total = 0;
            const rows = document.querySelectorAll('.order-item-row');
            
            rows.forEach((row, index) => {
                const select = row.querySelector('select');
                const quantityInput = row.querySelector('.quantity-input');
                const priceSpan = row.querySelector('.item-price');
                
                if (select && select.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price) || 0;
                    const quantity = parseInt(quantityInput.value) || 1;
                    const itemTotal = price * quantity;
                    
                    priceSpan.textContent = `Rp ${formatNumber(itemTotal)},-`;
                    total += itemTotal;
                } else {
                    priceSpan.textContent = 'Rp 0,-';
                }
            });

            document.getElementById('subtotal').textContent = `Rp ${formatNumber(total)},-`;
            document.getElementById('total').textContent = `Rp ${formatNumber(total)},-`;
        }

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Add first item row automatically
        addOrderItem();
    </script>
</body>
</html>