# Base de Datos – HackMTY App

Este documento describe las tablas, campos y relaciones de la base de datos de la aplicación para encontrar lugares y descuentos cerca del Tec.

---

## 1. Users
Almacena la información de los usuarios.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| name | varchar | Nombre del usuario |
| email | varchar | Correo electrónico |
| email_verified_at | timestamp | Fecha de verificación |
| password | varchar | Contraseña |
| remember_token | varchar | Token de sesión |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Relaciones:**
- 1:N → `votes`  
- N:M → `roles` (a través de `model_has_roles`)  
- N:M → `permissions` (a través de `model_has_permissions`)  

---

## 2. Categories
Clasifica productos y descuentos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| name | varchar | Nombre de la categoría |

**Relaciones:**
- 1:N → `products`  
- 1:N → `discounts`  

---

## 3. Places
Locales o restaurantes.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| name | varchar | Nombre del lugar |
| latitude | geometry | Latitud |
| longitude | geometry | Longitud |
| image_url | varchar | URL de la imagen |

**Relaciones:**
- 1:N → `discounts`  
- 1:N → `place_schedules`  
- N:M → `products` (a través de `places_products`)  

---

## 4. Place Schedules
Horarios de apertura de los lugares.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| place_id | integer | FK → `places.id` |
| day_of_week | tinyint | 0 = domingo ... 6 = sábado |
| open_time | time | Hora de apertura |
| close_time | time | Hora de cierre |

---

## 5. Discounts
Promociones de los lugares.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| title | varchar | Título del descuento |
| description | text | Descripción del descuento |
| image_url | varchar | Imagen del descuento |
| category_id | integer | FK → `categories.id` |
| place_id | integer | FK → `places.id` |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

**Relaciones:**
- 1:N → `discount_schedules`  
- Polimórfico → `votes`  

---

## 6. Discount Schedules
Horarios de vigencia de los descuentos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| discount_id | integer | FK → `discounts.id` |
| day_of_week | tinyint | Día de la semana |
| start_time | time | Inicio del descuento |
| end_time | time | Fin del descuento |

---

## 7. Products
Productos que se venden en los lugares.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| name | varchar | Nombre del producto |
| category_id | integer | FK → `categories.id` |
| image_url | varchar | Imagen del producto |

**Relaciones:**
- N:M → `places` (a través de `places_products`)  
- Polimórfico → `votes`  

---

## 8. Places_Products
Tabla intermedia que indica qué productos se venden en cada lugar y su precio.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| product_id | integer | FK → `products.id` |
| place_id | integer | FK → `places.id` |
| price | integer | Precio del producto en ese lugar |
| created_at | datetime | Fecha de creación |
| updated_at | datetime | Fecha de actualización |

---

## 9. Votes
Votos tipo “like/dislike” para productos y descuentos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| user_id | integer | FK → `users.id` |
| votable_type | varchar | 'product' o 'discount' |
| votable_id | integer | ID del producto o descuento |
| vote_value | integer | +1 / -1 |

---

## 10. Roles y Permissions
Control de acceso (Laravel Spatie).

**roles**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| name | varchar | Nombre del rol |
| guard_name | varchar | Guard (ej: web, api) |
| created_at | datetime | Fecha de creación |
| updated_at | datetime | Fecha de actualización |

**permissions**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | PK |
| name | varchar | Nombre del permiso |
| guard_name | varchar | Guard |
| created_at | datetime | Fecha de creación |
| updated_at | datetime | Fecha de actualización |

**Tablas intermedias**
- `role_has_permissions`: vincula roles con permisos  
- `model_has_roles`: vincula usuarios con roles  
- `model_has_permissions`: vincula usuarios con permisos directos  

---

## **Relaciones Clave**
- `users` → `votes`  
- `places` → `discounts`  
- `places` → `place_schedules`  
- `discounts` → `discount_schedules`  
- `products` ↔ `places` (N:M via `places_products`)  
- `categories` → `products` / `discounts`  
- `votes` → polimórfico hacia `products` o `discounts`  

---

## **Notas**
- Las imágenes se almacenan en AWS S3 y solo se guarda la URL en la base de datos.  
- Los horarios permiten filtrar lugares y descuentos activos “ahora”.  
- Se puede aplicar autenticación y roles para controlar quién puede crear descuentos o votar.  
