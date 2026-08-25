# LEOGATISTORE — CLAUDE.md

## 1. PROJECT IDENTITY

Project Name: LEOGATISTORE

Project Type:
Technology-focused E-Commerce & Commerce Management Ecosystem

Primary Business:

* Laptop
* Desktop PC
* Custom PC
* PC Components
* Monitor
* Computer Accessories
* Networking
* Printer
* Other technology products

Brand:
LEOGATISTORE

Brand Positioning:
Modern, trustworthy, professional technology commerce platform.

Primary Theme:
Blue Technology / Modern Commerce

Primary Brand Color:
#0B5CFF

Secondary Colors:
#071A3D
#063B9E
#EAF2FF
#F7F9FC
#FFFFFF

Typography:
Use a modern sans-serif typeface suitable for technology commerce.

Recommended:

* Inter
* Geist
* Plus Jakarta Sans

---

# 2. PROJECT OBJECTIVES

LEOGATISTORE is not intended to be a simple product catalog.

The system must evolve into an integrated technology commerce ecosystem consisting of:

1. Customer Storefront
2. Product Catalog
3. Product Specification Engine
4. Shopping Cart
5. Checkout
6. Payment
7. Order Management
8. Inventory Management
9. Warehouse Management
10. Supplier Management
11. Purchasing
12. PC Builder
13. Product Compatibility Engine
14. Serial Number Management
15. Warranty Management
16. Customer Management
17. Promotion Management
18. Review & Rating
19. Reporting
20. Notification
21. Admin Management
22. Security & Audit Logging
23. API Layer
24. Future Marketplace Integration

The architecture must support future expansion without requiring a complete rewrite.

---

# 3. TECHNOLOGY STACK

## Backend

* PHP 8.3+
* Laravel
* Laravel Blade
* Laravel Eloquent ORM
* Laravel Validation
* Laravel Policies
* Laravel Queues
* Laravel Events & Listeners
* Laravel Notifications
* Laravel Scheduler
* Laravel Sanctum for API authentication

## Frontend

* Blade
* Tailwind CSS
* Alpine.js
* Vanilla JavaScript where appropriate

Do not introduce React/Vue unless explicitly required.

## Database

* MySQL 8+

## Cache / Queue

* Redis

## Search

Preferred:

* Meilisearch

Fallback:

* MySQL FULLTEXT

## Storage

Private storage for sensitive files.

Use:

* Laravel Filesystem
* Local storage during development
* S3-compatible storage for production when necessary

## Infrastructure

Development:

* Laravel Herd / Laragon / Docker

Production:

* Linux
* Nginx
* PHP-FPM
* MySQL
* Redis
* Supervisor
* SSL/TLS

---

# 4. ARCHITECTURAL PRINCIPLE

Use a MODULAR MONOLITH architecture.

Do not start with microservices.

The system should be internally separated into business modules while remaining inside one Laravel application.

Recommended module boundaries:

```text
app/
├── Domain/
│   ├── Catalog/
│   ├── Customer/
│   ├── Cart/
│   ├── Checkout/
│   ├── Order/
│   ├── Payment/
│   ├── Shipping/
│   ├── Inventory/
│   ├── Warehouse/
│   ├── Purchasing/
│   ├── PCBuilder/
│   ├── Warranty/
│   ├── Promotion/
│   ├── Review/
│   ├── Reporting/
│   ├── Notification/
│   └── Security/
│
├── Http/
├── Models/
├── Policies/
├── Services/
└── Support/
```

Do not create a giant controller.

Business logic must not be placed directly inside Blade templates.

---

# 5. CORE BUSINESS MODULES

## 5.1 Authentication

Supported account types:

* Super Admin
* Admin
* Warehouse Staff
* Sales Staff
* Finance Staff
* Customer

Customer registration may be enabled depending on business requirements.

Authentication requirements:

* Secure password hashing
* Session regeneration after authentication
* Login throttling
* Password reset
* Email verification where required
* Remember session
* Logout
* Device/session management where appropriate

Never store plaintext passwords.

---

# 6. ROLE & PERMISSION SYSTEM

## Super Admin

Full system access.

Capabilities:

* Manage administrators
* Manage staff
* Manage roles
* Manage permissions
* Manage products
* Manage inventory
* Manage orders
* Manage customers
* Manage suppliers
* Manage purchasing
* Manage warranties
* Manage promotions
* View reports
* View financial information
* View audit logs
* Configure system

## Admin

Business administration access.

Capabilities:

* Product management
* Category management
* Brand management
* Order management
* Customer management
* Promotion management
* Inventory viewing
* Reporting

Sensitive system configuration should remain restricted.

## Warehouse Staff

Capabilities:

* View inventory
* Stock receiving
* Stock movement
* Stock adjustment
* Serial number management
* Packing
* Fulfillment

Cannot manage system configuration.

## Sales Staff

Capabilities:

* Customer management
* Order creation
* Order viewing
* Product viewing
* POS-related operations if implemented

## Finance Staff

Capabilities:

* Payments
* Invoices
* Refunds
* Financial reports

## Customer

Capabilities:

* Browse products
* Search products
* Compare products
* Add to wishlist
* Add to cart
* Checkout
* Pay
* View orders
* Track shipments
* Review products
* Manage profile
* View warranty
* Create PC builds

---

# 7. PRODUCT CATALOG

Products must support structured data.

Do not store all specifications as one uncontrolled description.

Core entities:

```text
products
product_variants
product_images
product_specifications
brands
categories
```

A product may have multiple variants.

Example:

```text
ASUS ROG Laptop
├── 16GB / 512GB
├── 16GB / 1TB
└── 32GB / 1TB
```

Each variant should support:

* SKU
* Price
* Cost price
* Stock
* Weight
* Dimensions
* Barcode
* Status

---

# 8. PRODUCT SPECIFICATION ENGINE

Technology products require structured specifications.

Supported specification groups may include:

## Processor

* Brand
* Model
* Generation
* Core
* Thread
* Base Clock
* Boost Clock

## Memory

* Capacity
* Type
* Speed
* Number of Slots
* Maximum Capacity

## Storage

* Type
* Capacity
* Interface
* Read Speed
* Write Speed

## Display

* Size
* Resolution
* Panel
* Refresh Rate
* Brightness
* Response Time
* Color Coverage

## Graphics

* GPU Brand
* GPU Model
* VRAM
* Memory Type

## Connectivity

* WiFi
* Bluetooth
* USB
* HDMI
* DisplayPort
* LAN
* Audio

Specifications must be queryable.

---

# 9. CATEGORY STRUCTURE

Initial categories:

```text
Laptop
PC
PC Components
Monitor
Accessories
Networking
Printer
Storage
Gaming
Office Equipment
Software & Digital
```

Categories must support:

* Parent category
* Child category
* Slug
* Description
* Image
* Icon
* Sort order
* Active status
* SEO metadata

---

# 10. BRAND MANAGEMENT

Brands must be separate entities.

Examples:

* ASUS
* Lenovo
* HP
* Acer
* MSI
* Dell
* Gigabyte
* NVIDIA
* AMD
* Intel
* Logitech
* Razer
* Samsung
* LG

Do not hard-code brands in source code.

---

# 11. PRODUCT SEARCH

Search must support:

* Product name
* SKU
* Brand
* Category
* Specification
* Price range

Filters should include:

* Brand
* Category
* Price
* RAM
* Storage
* CPU
* GPU
* Display
* Availability

Search must be optimized for electronics catalog usage.

---

# 12. PRODUCT COMPARISON

Customers can select multiple products for comparison.

Comparison should display:

* Price
* Brand
* SKU
* Processor
* RAM
* Storage
* GPU
* Display
* Connectivity
* Warranty

Comparison must normalize specification fields.

---

# 13. PC BUILDER

PC Builder is a strategic feature.

Supported components:

```text
CPU
Motherboard
RAM
GPU
SSD
HDD
PSU
Case
CPU Cooler
Case Fan
Operating System
```

PC Builder must:

1. Allow component selection.
2. Calculate total price.
3. Calculate estimated power consumption.
4. Validate compatibility.
5. Recommend compatible components.
6. Display compatibility warnings.
7. Save builds.
8. Add complete build to cart.

---

# 14. PC COMPATIBILITY ENGINE

Compatibility rules may include:

CPU ↔ Motherboard

Motherboard ↔ RAM

Motherboard ↔ Case

GPU ↔ Case

GPU ↔ PSU

CPU ↔ Cooler

RAM ↔ Motherboard

Storage ↔ Motherboard

Power consumption ↔ PSU capacity

Never allow a confirmed incompatible build to be purchased without explicit override rules.

Compatibility status:

```text
compatible
warning
incompatible
unknown
```

---

# 15. CART

Cart must support:

* Product variant
* Quantity
* Price snapshot
* Stock validation
* Remove item
* Update quantity
* Coupon
* PC Builder build
* Shipping estimation

Never trust prices submitted by the client.

Prices must be revalidated server-side.

---

# 16. CHECKOUT

Checkout flow:

```text
Cart
↓
Address
↓
Shipping Method
↓
Payment Method
↓
Order Review
↓
Create Order
↓
Payment
↓
Order Processing
```

Order creation must be transactional.

Prevent duplicate orders.

---

# 17. ORDER STATUS

Recommended states:

```text
pending
awaiting_payment
paid
processing
packed
shipped
delivered
completed
cancelled
refunded
returned
```

State transitions must be controlled.

Do not allow arbitrary status changes.

---

# 18. PAYMENT

Payment integration must use a payment service abstraction.

Example interface:

```text
PaymentGatewayInterface
```

Possible gateways:

* Midtrans
* Xendit
* Other supported providers

Payment callbacks/webhooks must be:

* Verified
* Idempotent
* Logged
* Processed safely

Never trust payment status from frontend requests.

---

# 19. SHIPPING

Shipping module should support:

* Customer address
* Courier
* Service
* Tracking number
* Shipping cost
* Shipment status

Future integration may support Indonesian logistics providers.

Keep shipping providers behind an abstraction layer.

---

# 20. INVENTORY

Inventory is one of the core modules.

Entities:

```text
warehouses
inventory
inventory_movements
```

Stock changes must be recorded.

Movement types:

```text
purchase
sale
return
adjustment
transfer
damage
reservation
release
```

Never modify stock without creating an inventory movement.

---

# 21. WAREHOUSE

Warehouse capabilities:

* Stock receiving
* Stock transfer
* Stock adjustment
* Stock counting
* Picking
* Packing
* Shipment preparation

Support multiple warehouses in the future.

---

# 22. SERIAL NUMBER

Electronic products may require serial tracking.

Entity:

```text
serial_numbers
```

Each serialized item can contain:

* Serial number
* Product
* Variant
* Warehouse
* Purchase order
* Customer
* Order
* Warranty
* Status

Serial statuses:

```text
available
reserved
sold
returned
damaged
warranty
```

Serial number must be unique.

---

# 23. WARRANTY SYSTEM

Warranty must support:

* Warranty registration
* Warranty period
* Start date
* End date
* Serial number
* Customer
* Product
* Warranty claim

Customer-facing feature:

```text
Check Warranty
```

Input:

```text
Serial Number
```

Output:

```text
Product
Purchase Date
Warranty Period
Warranty End
Status
```

---

# 24. SUPPLIER

Supplier entity must support:

* Company name
* Contact person
* Email
* Phone
* Address
* Tax information if required
* Payment terms
* Status

---

# 25. PURCHASING

Flow:

```text
Supplier
↓
Purchase Order
↓
Goods Receipt
↓
QC
↓
Serial Registration
↓
Inventory
```

Purchase orders must support:

* Supplier
* Items
* Quantity
* Cost
* Expected delivery
* Status

---

# 26. PROMOTION

Support:

* Discount
* Voucher
* Coupon
* Flash sale
* Minimum purchase
* Product-specific discount
* Category discount
* Brand discount
* Free shipping where supported

Promotion rules must be validated server-side.

---

# 27. REVIEW & RATING

Only verified purchasers should be allowed to review products.

Support:

* Rating
* Comment
* Images
* Verified purchase
* Moderation
* Admin response

---

# 28. CUSTOMER DASHBOARD

Customer dashboard:

```text
Dashboard
Orders
Order Detail
Wishlist
Reviews
Addresses
Profile
Security
Saved PC Builds
Warranty
Notifications
```

---

# 29. ADMIN DASHBOARD

Dashboard metrics:

* Total sales
* Orders
* Revenue
* Customers
* Products
* Low stock
* Pending payments
* Pending shipments
* Returns
* Warranty claims

Charts should be useful and not decorative.

---

# 30. REPORTING

Reports should include:

## Sales

* Daily sales
* Monthly sales
* Annual sales
* Product sales
* Category sales
* Brand sales

## Inventory

* Current stock
* Low stock
* Stock movement
* Dead stock

## Purchasing

* Supplier purchases
* Purchase history
* Cost analysis

## Customers

* New customers
* Repeat customers
* Customer spending

---

# 31. NOTIFICATIONS

Channels:

* In-app
* Email
* WhatsApp integration in future

Notification events:

* Order created
* Payment received
* Order shipped
* Order delivered
* Warranty ending
* Stock low
* Promotion

---

# 32. AUDIT LOG

Critical actions must be logged.

Examples:

```text
login
logout
failed_login
create_product
update_product
delete_product
stock_adjustment
create_order
payment_update
refund
warranty_claim
user_permission_change
system_setting_change
```

Log:

* User
* Action
* Target
* IP
* User agent
* Timestamp
* Metadata

Logs must not expose passwords or payment secrets.

---

# 33. SECURITY

Mandatory security practices:

* CSRF protection
* XSS protection
* SQL injection prevention
* Authorization policies
* Rate limiting
* Secure password hashing
* Session regeneration
* Secure cookies
* Input validation
* Output escaping
* File upload validation
* MIME validation
* Access control
* Audit logging
* Webhook signature verification
* Server-side price validation

Never trust:

* Client-side price
* Client-side role
* Client-side stock
* Client-side payment status
* Hidden form fields
* JavaScript authorization

---

# 34. FILE UPLOAD SECURITY

Allowed uploads must be explicitly validated.

For product images:

* JPEG
* PNG
* WebP

For documents:

* PDF where required

Use:

* MIME validation
* Extension validation
* File size limits
* Randomized filenames

Never execute uploaded files.

---

# 35. SEO

Public product pages should support:

* SEO title
* Meta description
* Canonical URL
* Open Graph
* Product structured data
* Breadcrumb structured data
* Sitemap
* Robots rules

Product URLs:

```text
/products/{slug}
```

Category:

```text
/categories/{slug}
```

Brand:

```text
/brands/{slug}
```

---

# 36. URL STRUCTURE

Public:

```text
/
 /products
 /products/{slug}
 /categories/{slug}
 /brands/{slug}
 /compare
 /pc-builder
 /cart
 /checkout
 /orders
 /warranty/check
```

Admin:

```text
/admin
/admin/products
/admin/categories
/admin/brands
/admin/orders
/admin/customers
/admin/inventory
/admin/warehouses
/admin/suppliers
/admin/purchases
/admin/warranties
/admin/promotions
/admin/reports
/admin/users
/admin/roles
/admin/activity-logs
```

---

# 37. DATABASE CORE

Core tables:

```text
users
roles
permissions
role_user
permission_role

products
product_variants
product_images
product_specifications
brands
categories

pc_builds
pc_build_items
component_compatibilities

warehouses
inventory
inventory_movements
serial_numbers

suppliers
purchase_orders
purchase_order_items
goods_receipts

addresses

carts
cart_items
wishlists

orders
order_items
payments
shipments
invoices

reviews

promotions
coupons

warranties
warranty_claims

notifications
activity_logs
settings
```

---

# 38. DATABASE RULES

Use:

* Primary keys
* Foreign keys
* Unique constraints
* Indexes
* Composite indexes where necessary
* Soft deletes where appropriate

Important unique fields:

```text
users.email
products.slug
product_variants.sku
serial_numbers.serial_number
categories.slug
brands.slug
orders.order_number
```

Do not duplicate business data unnecessarily.

Use database transactions for:

* Order creation
* Payment processing
* Stock deduction
* Purchase receiving
* Stock transfer
* Refund

---

# 39. CODING STANDARDS

Follow:

* PSR-12
* Laravel conventions
* SOLID principles
* DRY
* Separation of concerns

Controllers should coordinate.

Services should contain complex business logic.

Models should contain relationships and appropriate model behavior.

Policies should handle authorization.

Form Requests should handle validation.

Jobs should handle asynchronous tasks.

Events should handle decoupled business reactions.

---

# 40. FRONTEND RULES

Design must be:

* Responsive
* Mobile-first
* Accessible
* Fast
* Clean
* Professional

Desktop:

* Wide product grid
* Sidebar filters
* Detailed comparison
* Advanced admin tables

Mobile:

* Bottom navigation where appropriate
* Sticky cart/checkout actions
* Collapsible filters
* Touch-friendly controls
* Responsive product cards

Do not create desktop-only interfaces.

---

# 41. DESIGN SYSTEM

Primary:

```text
Blue: #0B5CFF
Navy: #071A3D
```

Use blue for:

* Primary CTA
* Links
* Active states
* Important highlights

Avoid excessive gradients.

Cards:

* 12px–20px radius
* subtle borders
* subtle shadows

Buttons:

* clear hierarchy
* accessible contrast
* visible hover/focus state

---

# 42. PERFORMANCE

Requirements:

* Avoid N+1 queries
* Use eager loading
* Paginate large datasets
* Cache expensive queries
* Optimize images
* Lazy load images
* Queue email/notifications
* Index searchable columns
* Avoid loading unnecessary columns

Admin tables must be paginated.

Never load thousands of records into memory unnecessarily.

---

# 43. TESTING

Required test categories:

## Unit

Business rules.

## Feature

HTTP flows.

## Integration

Payment, shipping, inventory.

## Authorization

Role/permission boundaries.

Critical tests:

* Login
* Product creation
* Cart
* Checkout
* Order creation
* Payment webhook
* Stock deduction
* Stock restoration
* PC compatibility
* Warranty lookup
* Admin authorization

---

# 44. DEVELOPMENT PHASES

## Phase 1 — Foundation

* Laravel setup
* Authentication
* Roles
* Permissions
* Admin layout
* Customer layout
* Database foundation

## Phase 2 — Catalog

* Products
* Categories
* Brands
* Variants
* Images
* Specifications
* Search
* Filters

## Phase 3 — Commerce

* Cart
* Checkout
* Addresses
* Orders
* Payment abstraction
* Invoice

## Phase 4 — Inventory

* Warehouse
* Stock
* Stock movements
* Serial numbers

## Phase 5 — Purchasing

* Suppliers
* Purchase orders
* Goods receipt

## Phase 6 — PC Builder

* Components
* Compatibility
* Power calculation
* Saved builds

## Phase 7 — Warranty

* Warranty registration
* Warranty lookup
* Claims

## Phase 8 — Customer Experience

* Wishlist
* Reviews
* Notifications
* Promotions
* Comparison

## Phase 9 — Reporting

* Sales
* Inventory
* Customers
* Purchasing

## Phase 10 — Omnichannel

* Marketplace integration
* Shipping integration
* Payment integration
* Public API

---

# 45. DEVELOPMENT PRIORITY

Priority order:

```text
P0 = Security / Data Integrity
P1 = Core Commerce
P2 = Inventory
P3 = Business Operations
P4 = Customer Experience
P5 = Advanced Features
```

Do not build decorative features before core commerce is stable.

---

# 46. NON-NEGOTIABLE RULES

1. Do not bypass authorization.
2. Do not trust client-side financial values.
3. Do not expose private data.
4. Do not hard-code business configuration.
5. Do not put business logic in Blade.
6. Do not create giant controllers.
7. Do not modify database structure without migration.
8. Do not delete production data casually.
9. Do not implement payment without webhook verification.
10. Do not change inventory without movement records.
11. Do not expose serial numbers unnecessarily.
12. Do not store plaintext passwords.
13. Do not introduce unnecessary dependencies.
14. Do not rewrite existing modules without understanding their dependencies.
15. Do not mark a feature complete without testing it.

---

# 47. DEFINITION OF DONE

A feature is complete only when:

* Database migration exists.
* Model exists where required.
* Relationships are defined.
* Validation exists.
* Authorization exists.
* Controller/service exists.
* UI exists.
* Responsive behavior works.
* Error handling exists.
* Empty state exists.
* Loading state exists where appropriate.
* Tests exist for critical behavior.
* Security implications have been considered.
* No obvious N+1 query exists.
* Documentation is updated where necessary.

---

# 48. PROJECT PRINCIPLE

Build LEOGATISTORE as a serious commerce platform.

The goal is not:

"Make a website that looks like an online store."

The goal is:

"Build a maintainable technology commerce ecosystem that can operate real product sales, inventory, purchasing, fulfillment, warranty, and customer operations."

Every implementation decision must support that goal.
