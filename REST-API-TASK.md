> **Author's Note**
> 
> This test assignment was originally authored by me during my tenure as a Tech Lead to evaluate candidates' skills in
> API design, database architecture (PostGIS), and clean code practices.
>
> I have chosen to implement this reference solution using **PHP 8.4** and **Yii3** to demonstrate modern
> high-performance standards, deep observability, and architectural patterns that go beyond basic CRUD requirements.

# Test Assignment

Implement a REST API service for finding the nearest stock by coordinates.

Each warehouse belongs to a specific city. For all entities, implement a JSON REST API and provide documentation in
OpenAPI format.

---

## Cities

### City model

```json
{
    "city_id": "int | uuid",
    "name": "string",
    "created_at": "ISO-8601-datetime"
}
```

### List cities

```http
GET /cities HTTP/1.1
```

### Get city

```http
GET /cities/{id} HTTP/1.1
```

### Create city

```http
POST /cities HTTP/1.1
Content-Type: application/json
Authorization: Bearer *****

{
  "name": "Minsk"
}
```

### Update city

```http
PATCH /cities/{id} HTTP/1.1
Content-Type: application/json
Authorization: Bearer *****

{
  "name": "Minsk"
}
```

### Delete city

```http
DELETE /cities/{id} HTTP/1.1
Authorization: Bearer *****
```

### List stocks of a city

```http
GET /cities/{id}/stocks HTTP/1.1
```

---

## Stocks (Warehouses)

### Warehouse model

```json
{
    "stock_id": "int | uuid",
    "city_id": "int | uuid",
    "address": "string",
    "lat": "float",
    "lng": "float",
    "created_at": "ISO-8601-datetime",
    "updated_at": "ISO-8601-datetime"
}
```

### List all stocks

```http
GET /stocks HTTP/1.1
```

### Get stock

```http
GET /stocks/{id} HTTP/1.1
```

### Create stock

```http
POST /stocks HTTP/1.1
Content-Type: application/json
Authorization: Bearer *****

{
  "city_id": 1,
  "address": "Plehanova str, home 2",
  "lat": 12.323232,
  "lng": 34.343432
}
```

### Update stock

```http
PATCH /stocks/{id} HTTP/1.1
Content-Type: application/json
Authorization: Bearer *****

{
  "city_id": 1,
  "address": "Plehanova str, home 2",
  "lat": 12.323232,
  "lng": 34.343432
}
```

### Delete stock

```http
DELETE /stocks/{id} HTTP/1.1
Authorization: Bearer *****
```

---

## Find nearest stock by coordinates

Distance must be returned in meters, rounded to 2 decimal places.

```http
POST /stocks/nearby HTTP/1.1
Content-Type: application/json

{
  "lat": 23.323232,
  "lng": 23.212143
}
```

### Expected response

```json
{
    "stock": {
        "stock_id": 123,
        "address": "Pushkina str, home Kolotushkina",
        "lat": 23.323212,
        "lng": 23.212223,
        "city": {
            "city_id": 1,
            "name": "Minsk"
        }
    },
    "distance_meters": 100.00
}
```

The **Find nearest stock** endpoint must return both the warehouse, the calculated distance from the provided
coordinates, and the related city entity.

---

## Authentication

All endpoints that include the **Authorization** header must use **Bearer token** authentication.

The authentication mechanism itself is not required. It is sufficient to validate a static token defined in
configuration (for example, stored in `.env`).

---

## Pagination

City and stock listing endpoints must support pagination in the following format:

```json
{
    "data": "array<City | Stock>",
    "page": "int",
    "max_page": "int"
}
```

---

## API Documentation

API documentation must be provided in **OpenAPI** format (Stoplight can be used for viewing).

---

## Optional requirements

- Caching for coordinate-based search using Redis (optional)
- Usage of **PostGIS** is a plus  
  (`geography(Point, 4326)` type with a GIST index)

---

## Tech stack

- PHP >= 8.1
- MVC-compatible framework (~~preferably~~ except Laravel 10/11)
- PostgreSQL
- Redis as key-value storage for caching (optional)

---

> **Author's Note**
>
> I originally designed this test assignment myself while working as a Tech Lead to recruit new developers. In the
> original version, there was a "Laravel preferred" hint, as it was a pragmatic choice for the team
> at that time.
>
> I have now revisited this case to explore the capabilities of **Yii3**. The **"except Laravel"**
> constraint was added for a joke, but also to demonstrate an implementation on a more strict and "transparent" tech
> stack. Currently, this assignment is not used by me for hiring now (because now I am regular backend engineer, not a hiring manager or team/tech lead); it serves exclusively as a foundation for this
> technological showcase.
