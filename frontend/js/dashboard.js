// Simple dashboard JS to fetch counts and render a small donut chart
document.addEventListener('DOMContentLoaded', () => {
    const api = (typeof API_BASE !== 'undefined') ? API_BASE : 'http://127.0.0.1:8000/api';

    const el = id => document.getElementById(id);

    async function fetchOrders() {
        try {
            const res = await fetch(api + '/orders');
            if (!res.ok) return [];
            const json = await res.json();
            return json.data || [];
        } catch (e) { return []; }
    }

    async function fetchIngredients() {
        try {
            const res = await fetch(api + '/ingredients');
            if (!res.ok) return [];
            const json = await res.json();
            return json.data || [];
        } catch (e) { return []; }
    }

    async function fetchMenuItems() {
        try {
            const res = await fetch(api + '/menu-items');
            if (!res.ok) return [];
            const json = await res.json();
            return json.data || [];
        } catch (e) { return []; }
    }

    function updateOrdersView(orders) {
        el('totalOrders').textContent = orders.length;
        const counts = {dine_in:0,cathering:0,go_food:0,grab_food:0};
        orders.forEach(o => { counts[o.channel] = (counts[o.channel]||0)+1; });
        el('dineInCount').textContent = counts.dine_in || 0;
        el('catheringCount').textContent = counts.cathering || 0;
        el('goFoodCount').textContent = counts.go_food || 0;
        el('grabFoodCount').textContent = counts.grab_food || 0;
    }

    function updateItemsView(ingredients, menuItems) {
        const lowStock = ingredients.filter(i => i.is_low_stock).length;
        el('lowStockCount').textContent = lowStock;
        el('allItemCount').textContent = menuItems.length + ingredients.length;
        // group count is not implemented in models; show ingredient groups length if present
        el('groupCount').textContent = 0;
        drawDonut(((menuItems.length>0)? Math.round((menuItems.length/(menuItems.length+ingredients.length))*100):67));
    }

    function drawDonut(percent) {
        const canvas = document.getElementById('stockChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const size = canvas.width;
        ctx.clearRect(0,0,size,size);
        const cx = size/2, cy = size/2, r = (size/2)-6;
        // background ring
        ctx.beginPath(); ctx.arc(cx,cy,r,0,Math.PI*2); ctx.strokeStyle='#eee'; ctx.lineWidth=10; ctx.stroke();
        // foreground
        const end = (Math.PI*2) * (percent/100) - Math.PI/2;
        ctx.beginPath(); ctx.arc(cx,cy,r,-Math.PI/2,end); ctx.strokeStyle='#26a69a'; ctx.lineWidth=10; ctx.stroke();
        // center text
        ctx.fillStyle='#333'; ctx.font='14px sans-serif'; ctx.textAlign='center'; ctx.textBaseline='middle';
        ctx.fillText(percent + '%', cx, cy);
    }

    // navigation buttons
    const viewOrdersBtn = el('viewOrdersBtn');
    if (viewOrdersBtn) viewOrdersBtn.addEventListener('click', ()=> window.location.href='all-sales-order.html');
    const viewItemsBtn = el('viewItemsBtn');
    if (viewItemsBtn) viewItemsBtn.addEventListener('click', ()=> window.location.href='all-items.html');

    // initial load
    (async function init(){
        const [orders, ingredients, menuItems] = await Promise.all([fetchOrders(), fetchIngredients(), fetchMenuItems()]);
        updateOrdersView(orders);
        updateItemsView(ingredients, menuItems);
    })();
});
