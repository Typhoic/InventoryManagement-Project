# Frontend Setup & Connection Guide

## Frontend Structure

```
frontend/
├── ccb_project.html          (Main dashboard page)
├── charts-sample.html        (Chart.js example)
├── js/                       (JavaScript files)
│   ├── app.js               (Main app logic)
│   └── ... other JS files
├── mock/                     (Mock API responses for testing)
└── styles/                   (CSS stylesheets)

Root level:
├── app.js                    (Main app file)
├── styles/                   (CSS files)
└── images/                   (Image assets)
```

---

## How to Run the Frontend

### Option 1: Open HTML directly in browser (simplest)
```powershell
# Navigate to project root
cd 'd:\0Personal Files\College\New Inventory Management\InventoryManagement-Project'

# Open main page in default browser
Invoke-Item ccb_project.html

# Or open frontend sample
Invoke-Item frontend/charts-sample.html
```

### Option 2: Use a local web server (recommended for testing)
```powershell
# Using Python (built-in on Windows)
cd '...\InventoryManagement-Project'
python -m http.server 3000

# Then open http://localhost:3000 in your browser

# Or using Node.js (if installed)
npx http-server -p 3000
```

### Option 3: Use VS Code Live Server extension
1. Install "Live Server" extension in VS Code
2. Right-click `ccb_project.html` → "Open with Live Server"
3. Browser opens automatically at `http://localhost:5500`

---

## Connecting Frontend to Backend API

### Backend Base URL
Once the backend is running (see `backend/SETUP.md`):
```
Backend API: http://127.0.0.1:8000/api
```

### Example: Fetch Menu Items
Add this to your frontend JavaScript:

```javascript
// Fetch all menu items
async function loadMenuItems() {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/menu-items');
    const data = await response.json();
    
    console.log('Menu Items:', data);
    
    // Use data.data to get the array of menu items
    if (data.success) {
      displayMenuItems(data.data);
    }
  } catch (error) {
    console.error('Error fetching menu items:', error);
  }
}

function displayMenuItems(items) {
  const container = document.getElementById('menu-container');
  
  items.forEach(item => {
    const html = `
      <div class="menu-item">
        <img src="${item.image_url}" alt="${item.name}">
        <h3>${item.name}</h3>
        <p>${item.description}</p>
        <span class="price">Rp ${item.price.toLocaleString('id-ID')}</span>
        <button onclick="addToCart(${item.id})">Add to Cart</button>
      </div>
    `;
    container.innerHTML += html;
  });
}

// Call on page load
loadMenuItems();
```

### Example: Create an Order
```javascript
async function createOrder(orderData) {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        channel: 'dine_in',  // dine_in, cathering, go_food, grab_food
        total_amount: 50000,
        status: 'completed',
        items: [
          {
            menu_item_id: 1,
            quantity: 2,
            price: 25000
          }
        ]
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('Order created:', data.data);
      alert('Order placed successfully!');
    } else {
      console.error('Order failed:', data);
    }
  } catch (error) {
    console.error('Error creating order:', error);
  }
}
```

### Example: Get Low Stock Ingredients
```javascript
async function showLowStockAlerts() {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/ingredients/low-stock');
    const data = await response.json();
    
    if (data.data && data.data.length > 0) {
      const alerts = data.data.map(ing => 
        `⚠️ ${ing.name} is running low (${ing.stock_percentage.toFixed(1)}% remaining)`
      ).join('\n');
      
      alert('Low Stock Alerts:\n' + alerts);
    }
  } catch (error) {
    console.error('Error fetching low stock items:', error);
  }
}
```

---

## Available API Endpoints Summary

### Menu Items
```javascript
// GET all menu items
fetch('http://127.0.0.1:8000/api/menu-items')

// GET single menu item (with ingredients)
fetch('http://127.0.0.1:8000/api/menu-items/1')

// POST create menu item
fetch('http://127.0.0.1:8000/api/menu-items', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    name: 'New Bowl',
    price: 25000,
    description: 'Description',
    is_active: true
  })
})

// PUT update menu item
fetch('http://127.0.0.1:8000/api/menu-items/1', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ price: 26000 })
})

// DELETE menu item
fetch('http://127.0.0.1:8000/api/menu-items/1', { method: 'DELETE' })
```

### Orders
```javascript
// GET all orders
fetch('http://127.0.0.1:8000/api/orders')

// GET single order
fetch('http://127.0.0.1:8000/api/orders/1')

// POST create order
fetch('http://127.0.0.1:8000/api/orders', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    channel: 'dine_in',
    total_amount: 50000,
    status: 'completed',
    items: [{ menu_item_id: 1, quantity: 2, price: 25000 }]
  })
})

// PUT update order status
fetch('http://127.0.0.1:8000/api/orders/1', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ status: 'cancelled' })
})
```

### Ingredients
```javascript
// GET all ingredients
fetch('http://127.0.0.1:8000/api/ingredients')

// GET low stock ingredients
fetch('http://127.0.0.1:8000/api/ingredients/low-stock')

// GET single ingredient
fetch('http://127.0.0.1:8000/api/ingredients/1')

// PUT update ingredient stock
fetch('http://127.0.0.1:8000/api/ingredients/1', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ current_stock: 4500 })
})
```

---

## Testing Endpoints with Postman

1. Download **Postman** (free version available)
2. Create requests for each endpoint
3. Test GET/POST/PUT/DELETE operations
4. Export collection for team sharing

Or use **VS Code Thunder Client** extension (simpler alternative)

---

## CORS Issues?

If you see "CORS error" in browser console:
- Ensure backend is running on `http://127.0.0.1:8000`
- CORS should already be configured in `backend/config/cors.php`
- If issues persist, check browser console for exact error message

---

## Next Steps

1. ✅ Start the backend (`php artisan serve --port=8000`)
2. ✅ Open frontend HTML in browser
3. ✅ Add fetch calls to connect frontend to API (use examples above)
4. ✅ Test each endpoint with Postman or browser console
5. ✅ Build the dashboard UI once endpoints are working

---

## Need Help?

- Backend issues? See `backend/SETUP.md`
- API structure? Check `backend/routes/api.php`
- Controller logic? See `backend/app/Http/Controllers/Api/`
