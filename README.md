
````markdown
# Gas Distribution Management System (DMS)

A comprehensive, role based web application for managing gas distribution operations. This system handles the complete supply chain lifecycle from purchasing stock (POs) to warehouse entry (GRN) and final customer delivery (Logistics).

Built with **Laravel 11**, **Tailwind CSS**, and **Alpine.js**, adhering to strict MVC architecture and secure role-based access control (RBAC).

---

##  Key Features

### 🏢 Admin Portal (Management & Audit)
* **Purchase Order (PO) Management:** Create and approve stock orders with auto generated PO numbers.
* **GRN & Inventory:** Validate incoming stock against POs. Automatic stock updates upon approval.
* **Financial Auditing:** Track supplier payments, reconcile invoices vs. POs, and view real time ledgers.
* **Logistics & Routing:** Schedule delivery trucks, assign drivers, and track "Planned vs. Actual" delivery times.
* **Reporting:** Generate PDF audit reports with refill analysis and financial summaries (using DomPDF).
* **Executive Dashboard:** Visual charts and KPIs for revenue, pending orders, and inventory alerts.

### 🚛 Staff Portal (Operations)
* **Order Taking:** Fast order entry with auto-calculated category pricing (Dealer/Commercial/Individual).
* **Route Manifest:** Drivers view assigned stops sorted by priority (Urgent 🔥).
* **Workflow Tracking:** Update status from `Pending` → `Loaded` → `Delivered`.

---

## 🛠️ Technology Stack

* **Backend:** PHP 8.2, Laravel 11 Framework
* **Frontend:** Blade Templates, Tailwind CSS, Alpine.js (for dynamic forms)
* **Database:** MySQL (Production), SQLite (Testing/CI)
* **Tools:** DomPDF (Reports), Chart.js (Dashboard Analytics)
* **CI** GitHub Actions (Automated Integrity Checks)

---

## 📂 Project Architecture

This project follows Domain-Driven Design principles, separating Admin logic from Staff operations for better security and maintainability.

```text
/distribution_system
│
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Admin          <-- (Logic: Suppliers, POs, Reports, Routes)
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── GrnController.php
│   │   │   │   ├── PurchaseOrderController.php
│   │   │   │   └── ...
│   │   │   └── Staff          <-- (Logic: Orders, Route Status)
│   │   │       ├── OrderController.php
│   │   │       └── RouteController.php
│   │   │
│   └── Models                 <-- (Database Models: Order, Grn, Supplier, etc.)
│
├── database
│   ├── migrations             <-- (Database Schema Definitions)
│   └── seeders                <-- (Default Data: PricingSeeder, RouteSeeder)
│
├── resources
│   └── views
│       ├── components
│       │   ├── sysadmin       <-- (Reusable Admin Components)
│       │   └── staff          <-- (Reusable Staff Components)
│       │
│       ├── sysadmin           <-- (Admin Pages: /admin/*)
│       │   ├── GRN
│       │   ├── purchase_orders
│       │   ├── refill         <-- (Reports & PDF)
│       │   └── routes
│       │
│       └── staff              <-- (Staff Pages: /staff/*)
│           ├── dashboard.blade.php
│           └── orders
│
├── routes
│   └── web.php                <-- (Role-based Routing: 'role:admin' vs 'role:staff')
│
└── .github
    └── workflows
        └── ci.yml             <-- (CI/CD Pipeline Configuration)
````

-----

## ⚙️ Installation & Setup

Follow these steps to set up the project locally.

### 1\. Clone the Repository

```bash
git clone [https://github.com/sahansara/gas-distribution-system.git](https://github.com/sahansara/Gas_distribution_system.git)
cd gas-distribution-system
```

### 2\. Install Dependencies

```bash
# Backend Dependencies
composer install

# Frontend Dependencies
npm install
npm run build
```

### 3\. Environment Configuration

Duplicate the example environment file and configure your database.

```bash
cp .env.example .env
php artisan key:generate
```

*Edit `.env` and set your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.*

### 4\. Database Setup (Crucial)

Run migrations and seeders to populate Roles, Gas Types, Pricing, and Delivery Routes.

```bash
php artisan migrate --seed
```

*Note: The `--seed` flag is required to create the Admin/Staff accounts and default Pricing Logic.*

### 5\. Run the Application

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

-----

## 🔐 Default Credentials

Use these accounts to test the Role-Based Access Control (RBAC).

| Role | Email | Password | Access Scope |
| :--- | :--- | :--- | :--- |
| **System Admin** | `admin@example.com` | `password` | Full Control, Financials, Approvals |
| **Staff / Driver** | `staff@example.com` | `password` | Order Entry, Route Updates |

-----
## 📜 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```
```