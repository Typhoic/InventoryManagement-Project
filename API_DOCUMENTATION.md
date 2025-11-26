# API Documentation (summary)

This file lists the main API endpoints provided by the backend for frontend integration. All API paths are prefixed with `/api` when the backend root is served (e.g., `http://localhost:8000/api/...`). If you run the app via the repository layout (backend served from `/backend/public`), adjust paths accordingly.

## POST /api/reports/weekly
Create a weekly report and update ingredient snapshot.

Request payload (example):

```json
{
  "week_start": "2025-11-24",
  "sold_counts": [{ "product_id": 1, "quantity": 120 }],
  "total_income": 3750000,
  "ingredient_stock": [
    { "ingredient_id": 1, "quantity_on_hand": 15, "unit": "kg" }
  ]
}
```

Notes:
- Manager can provide `ingredient_stock` in kg (for solids) or L (for liquids); the backend will normalize units to base units (kg->g, L->ml) before saving.

Response sample:

```json
{
  "report_id": 12,
  "shortages": [
    { "ingredient_id": 1, "name": "Chicken Breast", "unit": "g", "required": 24000, "available": 15000, "need_to_buy": 9000, "will_suffice": false }
  ],
  "updated_ingredients": { "1": 15000 }
}
```

## GET /api/products/{id}/can-make?quantity=N
Checks if a product can be made for the requested quantity. Returns shortages if any.

Response sample:

```json
{
  "product_id": 1,
  "can_make": false,
  "shortages": [ { "ingredient_id": 1, "needed": 9000, "unit": "g" } ]
}
```

## GET /api/sales/chart/data
Returns chart-ready sales data (labels + dataset). Example shape:

```json
{ "labels": ["Mon","Tue","Wed"], "data": [120, 80, 150] }
```

---
If you need more endpoints or example responses, tell me which frontend views you want to implement and I will add expanded mock responses under `frontend/mock/` and update this file.
