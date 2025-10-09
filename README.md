
## 🏦 Laravel Banking API – Modular Transfer & SMS System

### 📋 Overview

This project is a **modular banking API** built with **Laravel 12**, designed to simulate internal bank transfers between credit cards and demonstrate clean architecture, service-based design, and extendable communication modules (e.g., SMS & Payment Gateways).

---

### ⚙️ Features

* 💳 **Card-to-card transfer system**

  * Validates Iranian card numbers (BIN + Luhn)
  * Supports Persian/Arabic/English digits
  * Atomic DB transactions with `lockForUpdate`
  * Fixed transaction fee handling (₮500)
* 🧾 **Transaction & Fee Logging**

  * Independent tables for `transactions` and `transaction_fees`
  * Reference tracking and timestamps
* 📲 **SMS Notification System**

  * Modular architecture (Strategy + Factory Pattern)
  * Implemented with **Kavenegar** provider
  * Ready for future expansion (e.g., Ghasedak)
* 📊 **Analytics API**

  * Returns top 3 users with the most transactions in the last 10 minutes
  * Includes last 10 transactions for each user
* 🧱 **Clean Service Architecture**

  * `TransferService`, `SmsService`, and `PaymentService` (future feature)
  * Fully RESTful endpoints

---

### 🗂️ Main Endpoints

| Method | Endpoint                   | Description                                           |
| ------ | -------------------------- | ----------------------------------------------------- |
| `POST` | `/api/transfers`           | Perform card-to-card transfer                         |
| `GET`  | `/api/analytics/top-users` | Get top 3 users with most transactions in last 10 min |

---

### 🧩 Folder Structure

```
app/
 ├── Http/
 │    ├── Controllers/
 │    │    ├── TransferController.php
 │    │    └── AnalyticsController.php
 │    └── Requests/TransferRequest.php
 ├── Models/
 ├── Services/
 │    ├── TransferService.php
 │    └── Sms/
 │         ├── SmsService.php
 │         ├── Contracts/SmsProviderInterface.php
 │         ├── Providers/KavenegarProvider.php
 │         └── SmsProviderFactory.php
 ├── Helpers/helpers.php
 └── ...
```

---

### 🧰 Configuration

Create `.env` and set:

KAVEHNEGAR_API_KEY=your_api_key
KAVEHNEGAR_SENDER=10004346
SMS_PROVIDER=kavenegar

For the payment gateways (future feature):

PAYMENT_GATEWAY=fake
ZARINPAL_MERCHANT_ID=your_id

---

### 🚀 Run Locally


composer install
php artisan migrate --seed
php artisan serve


**Test transfer API:**

POST /api/transfer
{
  "from_card": "6274123412341234",
  "to_card": "5022290112345678",
  "amount": "50000"
}


---

### 🧠 Future Expansion

* Add `GhasedakProvider` to `Sms/Providers`
* Integrate sandbox payment gateways (Zarinpal, IdPay)
* Add queue-based SMS dispatch

---

### 🧑‍💻 Author : [Amirhosein Ayinie] Laravel Developer & Backend Engineer 📧 [ayinie2003@gmail.com] 🔗 LinkedIn | GitHub
Developed as a **training project** to demonstrate clean Laravel architecture, modular service layers, and extendable design patterns by amirhosein ayinie.

---
