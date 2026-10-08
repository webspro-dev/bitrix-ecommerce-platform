# REST API v1

## GET /api/v1/products.php

Parameters:

- `page`
- `limit`
- `q`
- `brand`
- `sort`

## POST /api/v1/order.php

JSON:

```json
{
  "person_type_id": 1,
  "phone": "+79990000000",
  "email": "customer@example.com",
  "address": "Krasnodar, Example street 1"
}
```

The order endpoint recalculates the basket server-side before saving the order.
