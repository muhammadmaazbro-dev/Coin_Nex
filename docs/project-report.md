# Coin_Nex · NextGen BudgetBee — Project Report

> Final Year / Semester Project documentation
> Platform: Pure PHP 8.2 + MySQL (MariaDB) on XAMPP — zero frameworks, zero paid APIs.

---

## 1. Introduction

**Coin_Nex** is a web-based personal finance tracker made specifically for university students. Where general apps assume salaries and subscriptions, Coin_Nex speaks the student's money language: allowance, part-time job, hostel rent, course books, mess and transport.

The project demonstrates a complete, production-shaped CRUD web application: authentication with role-based access (student/admin), layered MVC architecture, prepared-statement database access, CSRF protection, data import, charting, generated PDF reports, and a rule-based "AI" advisor that works 100% offline.

### 1.1 Objectives
- Provide a fast, friendly income/expense ledger for students.
- Apply the **20% savings rule** automatically (savings rate + guidance).
- Catch *where money leaks* through category breakdowns and personalized tips.
- Let users budget categories per month and be warned **before** overspending.
- Deliver a full admin panel for moderation and platform statistics.
- Demonstrate all the classic CS-programming techniques using only core PHP + MySQL.

### 1.2 Scope
- Web app served from `http://localhost/coin_nex/`.
- Data local (MySQL). No bank/API integration — privacy by design, fully offline-capable.

---

## 2. Tools & Technologies

| Layer      | Technology                                |
| ---------- | ----------------------------------------- |
| Language   | PHP 8.2 (`strict_types=1`, PDO)           |
| Database   | MySQL 10.4 (MariaDB), utf8mb4             |
| Front-end  | HTML5, CSS3 (custom design system), vanilla JS, Chart.js (vendored) |
| PDF export | Hand-written PDF writer in core PHP (`app/core/Pdf.php`) |
| Server     | Apache 2.4 (XAMPP)                        |
| Security   | bcrypt password hashing, CSRF tokens, prepared statements, role guards |

### Why no frameworks?
Judged on fundamental understanding. A custom router/DB layer shows real command of PHP rather than library usage, and keeps the footprint tiny and fast.

---

## 3. Architecture

### 3.1 MVC structure
```
public/index.php          → front controller (everything routed through it)
app/core/App.php          → router: path → [Controller, action, params]
app/core/Database.php     → PDO singleton + run/fetch/fetchAll/scalar/tableExists
app/core/Helper.php       → url(), csrf, flash, auth guards, money, json, …
app/core/Session.php      → session + flash messages
app/core/AIClassifier.php → keyword + category-usage "AI" categorizer
app/core/Pdf.php          → pure-PHP PDF document writer
app/controllers/          → Auth, Home, Dashboard, Transactions, Categories,
                            Budgets, Reports, Tips, Insights, Notifications, Profile, Admin
app/models/               → User, Category, Transaction, Budget, Tips, Insights
app/views/pages/          → one view per page + admin/ subfolder + auth/ subfolder
app/views/layouts/        → app-layout (sidebar app shell), auth-layout, guest-layout
```

**Request flow:** `public/index.php` boots config → `App::run()` parses the URI → resolves `SlugController::action()` → controller calls models/`Helper` → renders a view inside the shared layout.

### 3.2 Security model
- **Passwords:** `password_hash()`/`password_verify()` (bcrypt).
- **Reset tokens:** 64-hex random tokens; only SHA-256 hashes stored; 1-hour expiry.
- **CSRF:** every POST carries a session-bound token, verified with `hash_equals`.
- **SQLi:** all queries are prepared statements via PDO (no string-built SQL for input).
- **XSS:** all dynamic output escaped with `Helper::e()` (htmlspecialchars).
- **AuthZ:** `Helper::requireLogin()` + `Helper::requireAdmin()` gate every page; deactivated accounts cannot log in (checked at login).
- **Cookie:** HttpOnly, SameSite=Lax, session-only cookies.

### 3.3 Database design (ER summary)
Tables: `users`, `categories`, `transactions`, `budgets`, `tips`, `insights`, `notifications`, `password_resets`, `announcements`.

Key relationships:
- `users 1 ─ N categories` (personal categories; system defaults have `user_id=NULL`)
- `users 1 ─ N transactions` (FK cascade delete)
- `categories 1 ─ N transactions`
- `users 1 ─ N budgets`, `categories 1 ─ N budgets` (unique user+category+month → one budget per category-month)
- `users 1 ─ N tips / insights / notifications / password_resets`

Notable constraints:
- `budgets.month` stored as DATE = **first day** of month (e.g. `2026-09-01`) to avoid MySQL zero-date coercion; queried with `DATE_FORMAT(month,'%Y-%m')`.
- `notifications.notif_key` = `month|category` gives deduplicated budget alerts.
- `insights` unique on `(user_id, month)` → one AI summary per month.
- `tips` dedup by `user_id + tip_text`.

---

## 4. Functional modules

### 4.1 Authentication
- Register (name, email, password ≥ 8, academic year, allowance, savings goal).
- Login with flash welcome; role-based landing (admin → panel).
- Forgot/reset password via hashed one-time tokens.
- Deactivation from admin blocks login instantly.

### 4.2 Dashboard
- Month income, expense, balance, savings **%**, daily average.
- **Line chart:** last 6 months income vs expense.
- **Doughnut:** this month expense by category with legend.
- **AI Highlights:** savings-rate verdict, top category, biggest expense, suggested action.
- **Budget vs Actual** widget with progress bars + **on-page alert banner** when any budget hits/exceeds its threshold.
- **Saving tips cards:** top-3 ranked tips with bookmark/dismiss actions.
- Recent activity feed.

### 4.3 Transactions
- Full CRUD; filters (month, type, category, free-text search).
- Recurring (weekly/monthly): before listing, `applyDueRecurring()` materializes any missed periods — keyed by `Y-m`/`Y-W` so it never double-creates (idempotent).
- Duplicate-flag heuristic (same amount+description+category within 21 days, skipping recurring cadences of 7/14/21/28–31 days); refreshed on every create/update/delete/import, shown as a ⚠️ Dup badge in the list.
- AI category suggestion while typing (endpoint `transactions/suggest`); optional override recorded.

### 4.4 CSV import
- Two-step: upload+preview (parse, classify, flag errors) → confirm insert.
- `AIClassifier` maps vendor text like `metro` → Transport, `college book` → Academics.
- Downloadable CSV template.

### 4.5 Budgets & alerts
- Create/edit per category+month, alert threshold % (default 80).
- Dashboard/budget views show live progress.
- Alerts: at ≥80% warn; at ≥100% exceeded; dedupe via `notif_key`.
- Delete with confirm.

### 4.6 Reports
- Monthly filter, summary cards, per-category share bars, biggest transactions table.
- **Daily summary** (per-day income/expense/net within the month) and **Weekly summary** (ISO-week rollups) tables.
- **CSV export** (RFC-4180) and **PDF export** via the in-house `Pdf.php` (compressed streams, page numbers, headers/footers).
- **Localised currency:** all amounts render in Pakistani Rupees (`Rs 1,234.56`) via `Helper::money()`; the symbol is a single config switch (`DEFAULT_CURRENCY_SYMBOL`).

### 4.7 Saving Tips (rules engine)
- Individualized: subscriptions audit, hospitality overspend, transport upgrade, budget-cap, generic fallbacks.
- Ranked by `rank_score` (estimated monthly impact).
- Tabs: Active / Bookmarked / Dismissed; bookmark, dismiss, restore with CSRF.

### 4.8 AI Insights + chatbot
- `Insights::generateForUser(user, month)` — deterministic engine writing one summary/tip per month (unique constraint).
- **Bee chatbot** (`insights/chat`): plain-language answers on spend, savings rate, balance left, where money went, tips, budgets; quick-reply chips; 100% offline rules.
- Disclaimer shown that advice is a heuristic guide, not financial advice.

### 4.9 Admin panel
- Stats: users, all-time spending/income, active-this-month, category/budget/tip counts.
- Charts: money movement & signups (6 months).
- User management: search, view detail, activate/deactivate, promote/demote, permanent delete (cascades to their data).
- Guards prevent self-deactivation/deletion and admin-demotion of others.
- Notification purge utility.

### 4.10 UX polish
- Dark/light themes (persisted), font scaling, keyboard shortcuts (`/` search, N add, C categories, B budgets, R reports, I insights, T tips), toasts, skeleton loaders, print stylesheet, responsive sidebar.

---

## 5. Test plan & results

Manual + HTTP-driven end-to-end tests (curl with cookie jars) were run for every module:

| Area                      | Test cases (summary)                                     | Result |
| ------------------------- | -------------------------------------------------------- | ------ |
| Auth                      | register, login, wrong-pass, forgot/reset, deactivated blocks login | ✅ |
| Transactions              | add/edit/delete, filters, recurring idempotency, duplicate flag | ✅ |
| CSV import                | preview+confirm, AI auto-categorization, empty/error rows | ✅ |
| Categories                | create/update/delete own, cannot touch system defaults   | ✅ |
| Budgets                   | CRUD, 80% warn, 100% exceed, dedup on reload             | ✅ |
| Reports                   | page, CSV rows, PDF valid & decompresses                 | ✅ |
| Tips                      | generation, bookmark/dismiss/restore, tabs               | ✅ |
| Insights / chat           | monthly summary, savings/where/left/tip/help intents     | ✅ |
| Notifications             | list, mark-read, mark-all-read                           | ✅ |
| Admin                     | stats, search, ban blocks login, role toggle, delete     | ✅ |
| Data integrity            | emoji round-trip (utf8mb4), month DATE coercion, BOM-safe UTF-8 files | ✅ |

Bugs found & fixed during the run: recurring duplicate creation (rewritten period-based), `$cursor` not reset between CSV rows, `date()` TypeError on previous-month calc, `strpos` on "savings" keyword, `Database::run` returning `PDOStatement` mistaken for `bool` (removed wrong return types), double-encoded em/en dashes in tip text, missing `tableExists` helper, `budgets.month` zero-date coercion.

---

## 6. Deliverables

1. `database/coin_nex.sql` — full schema + demo data; creates DB itself.
2. `README.md` — install, accounts, feature list, structure, tech notes.
3. Demo accounts — admin & umar (87 seeded transactions).
4. Sitemap — on the home page (mandatory).
5. This report + ER diagram (see `docs/ER-Diagram.md`).
6. Hosted URL & project video — see submission pack.

---

## 7. Conclusion

Coin_Nex meets every SRS requirement for a student budget tracker while doubling as a showcase of core web-development craft: custom router, hand-rolled PDF, offline rule-based AI, bulletproof CRUD, and a polished, accessible UI — all in pure PHP and MySQL.

### Future scope
- Expense split/scanning of receipts (OCR).
- Multi-currency conversion.
- Shared household budgets between roommates (FK to a "household" group table).
- Push notifications and mobile app wrapper (PWA).