# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

# PayLink — CodeIgniter 4 Payment Link Prototype

A clean, maintainable prototype that lets a visitor generate a unique shareable payment page.

---

## Quick Start

Before running the application, do the following:
npm install

````
Copy and paste this in the .env file
CI_ENVIRONMENT = development

database.default.DBDriver = SQLite3


### 2. Run the migration

```bash
php spark migrate

To get the tailwindcss classes to apply
npm run dev

To run the application
php spark serve
````

## Routes

| Method | URI                      | Handler                    | Purpose                    |
| ------ | ------------------------ | -------------------------- | -------------------------- |
| GET    | `/`                      | `LinksController::index`   | Show the creation form     |
| POST   | `/links`                 | `LinksController::store`   | Validate, create, redirect |
| GET    | `/links/success/{token}` | `LinksController::success` | "Your link is ready" page  |
| GET    | `/pay/{token}`           | `PayController::show`      | Public payment page        |
| POST   | `/pay/{token}/process`   | `PayController::process`   | Simulate payment           |

---

## Architecture

```
HTTP Request
     │
     ▼
Controller  (thin — validates input, calls service, renders view)
     │
     ▼
PaymentLinkService  (business logic — orchestrates token generation & persistence)
     │            │
     ▼            ▼
PaymentLinkModel  TokenGenerator
(DB queries)      (crypto random tokens)
```

### Key design decisions

#### Service Layer (`app/Services/`)

`PaymentLinkService` owns all business logic and is the only class that talks to both the model and the token generator. Controllers stay thin and could be swapped for an API controller without changing any logic.

#### Token Generation (`TokenGenerator`)

- Uses `random_bytes(32)` → 256 bits of CSPRNG entropy.
- Encoded as URL-safe base64 (no `+`, `/`, or `=`) so the token is safe in a URL path segment with no percent-encoding.
- `PaymentLinkService` checks uniqueness in a retry loop (max 10 attempts); the DB also has a `UNIQUE` constraint as a safety net.

#### Validation (`app/Validation/PaymentLinkRules`)

All rules and messages are in one static class. This makes them:

- Reusable from an API controller without duplication.
- Easy to unit-test independently.

#### Security considerations

| Concern                | Mitigation                                                                                                                            |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------- |
| XSS                    | All output passed through `esc()` (CI4 built-in)                                                                                      |
| CSRF                   | `csrf_field()` on every POST form                                                                                                     |
| Token enumeration      | Tokens are 256-bit random — brute-force is computationally infeasible; `findByToken` returns the same null for any non-existent token |
| Token format injection | `isValidTokenFormat()` regex rejects unexpected characters before a DB query                                                          |
| SQL injection          | CI4 Query Builder uses parameterised queries throughout                                                                               |
| Double-submission      | Pay button is disabled on click via JS                                                                                                |
| Mass assignment        | `$allowedFields` on the model enforces a strict whitelist                                                                             |

---

## Database Schema

```sql
CREATE TABLE `payment_links` (
  `id`          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token`       VARCHAR(64)         NOT NULL,
  `email`       VARCHAR(255)        NOT NULL,
  `title`       VARCHAR(255)        NOT NULL,
  `description` TEXT                NOT NULL,
  `price`       DECIMAL(10,2)       NOT NULL,
  `currency`    VARCHAR(3)          NOT NULL DEFAULT 'USD',
  `status`      ENUM('pending','paid','expired') NOT NULL DEFAULT 'pending',
  `paid_at`     DATETIME            NULL,
  `created_at`  DATETIME            NULL,
  `updated_at`  DATETIME            NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_links_token_unique` (`token`),
  KEY `payment_links_email_index` (`email`)
);
```

---

## Extending the prototype

| Feature                                 | Where to add it                                                                                        |
| --------------------------------------- | ------------------------------------------------------------------------------------------------------ |
| Real payment gateway (Stripe, Paystack) | `PaymentLinkService::markAsPaid()` — inject a gateway client                                           |
| Link expiry                             | Add `expires_at` field; check in `PaymentLinkService::findByToken()`                                   |
| Email notifications                     | Add a `NotificationService`; call from `PaymentLinkService` after creation/payment                     |
| REST API endpoint                       | Add `App\Controllers\Api\LinksController`; reuse `PaymentLinkService` and `PaymentLinkRules` unchanged |
| Admin dashboard                         | New controller + model scopes; service layer already exposes the data                                  |
