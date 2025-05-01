# Laravel Banking App

This is a Laravel-based banking application that supports user registration, secure login with email-based two-factor authentication, account management, multi-currency support, and a simple admin panel.

---

## ✨ Features

### 🔐 Authentication
- Users can **register and log in**.
- **Email-based 2FA** (two-factor authentication) is required on login for added security.

### 👑 Admin Functionality
- The **first registered user is automatically assigned as an admin** via a migration.
- Admins have access to an **admin-only dashboard** to:
  - View all user accounts.
  - **Search and filter** accounts.
  - **Create new accounts** on behalf of users.
- This admin panel is **hidden from regular users**.

### 💳 Accounts
- Users can hold multiple accounts.
- **New accounts are assigned unique account numbers automatically**.
- Balances are stored in **USD by default**.

### 💸 Transfers
- Users can **transfer funds between accounts** (peer-to-peer).
- All transfers are recorded and shown in a **transaction history table**.

### 🌍 Multi-Currency Support
- Users can select a currency (USD, EUR, GBP) from a dropdown.
- The app uses the **Exchange Rates API** to fetch real-time exchange rates.
- When a currency is selected:
  - Account balances are dynamically converted and shown in that currency.
  - Transfers calculate the converted amount with a configurable **0.01 spread** if needed.

---

