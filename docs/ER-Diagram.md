# Coin_Nex · ER Diagram (text / Mermaid)

> Relations drawn from `database/coin_nex.sql`.

## Mermaid

```mermaid
erDiagram
    USERS ||--o{ CATEGORIES : "owns (personal)"
    USERS ||--o{ TRANSACTIONS : makes
    USERS ||--o{ BUDGETS : plans
    USERS ||--o{ TIPS : receives
    USERS ||--o{ INSIGHTS : "gets summary"
    USERS ||--o{ NOTIFICATIONS : gets
    USERS ||--o{ PASSWORD_RESETS : "request reset"
    CATEGORIES ||--o{ TRANSACTIONS : "classifies"
    CATEGORIES ||--o{ BUDGETS : "capped by"
    CATEGORIES o|--o{ TIPS : "targets"

    USERS {
        int user_id PK
        varchar name
        varchar email UK
        varchar password_hash
        enum role "student|admin"
        varchar academic_year
        decimal monthly_allowance_baseline
        decimal savings_goal
        varchar currency
        tinyint is_active
        enum theme "dark|light"
        decimal font_scale
        datetime last_login
        datetime created_at
    }
    CATEGORIES {
        int category_id PK
        varchar name
        enum type "income|expense"
        varchar icon
        tinyint is_default
        int user_id FK "NULL = system default"
    }
    TRANSACTIONS {
        int transaction_id PK
        int user_id FK
        int category_id FK
        enum type "income|expense"
        decimal amount
        varchar description
        date transaction_date
        tinyint is_recurring
        enum recurrence "none|weekly|monthly"
        int ai_suggested_category
        tinyint ai_override
        tinyint is_duplicate_flag
    }
    BUDGETS {
        int budget_id PK
        int user_id FK
        int category_id FK
        date month "first day of month"
        decimal limit_amount
        decimal alert_threshold
        UNIQUE user_id+category_id+month
    }
    TIPS {
        int tip_id PK
        int user_id FK
        int category_id FK "nullable"
        text tip_text
        decimal rank_score
        tinyint is_pinned
        tinyint is_dismissed
        tinyint bookmarked
    }
    INSIGHTS {
        int insight_id PK
        int user_id FK
        date month "first day of month"
        text summary_text
        text tip_text
        enum generated_by "engine|ai"
        tinyint is_pinned
        UNIQUE user_id+month
    }
    NOTIFICATIONS {
        int notification_id PK
        int user_id FK
        varchar message
        enum type "budget|info|flag"
        varchar related_type
        int related_id
        varchar notif_key "dedup key"
        varchar link
        tinyint is_read
    }
    PASSWORD_RESETS {
        int reset_id PK
        int user_id FK
        char token_hash "SHA-256"
        datetime expires_at
        tinyint used
    }
```

## Table-by-table (one line each)

| Table            | Purpose                                   | Key constraints                             |
| ---------------- | ----------------------------------------- | ------------------------------------------- |
| users            | Accounts (student/admin)                  | email UNIQUE; role/theme enums              |
| categories       | Income & expense buckets                  | user_id NULL = system default               |
| transactions     | Ledger rows                               | FK user+category; type enum; date indexed   |
| budgets          | Monthly category caps                      | UNIQUE(user,cat,month); threshold %         |
| tips             | Personalized saving tips                   | dedup user+tip_text; rank_score             |
| insights         | Monthly AI summaries                       | UNIQUE(user,month)                          |
| notifications    | Alerts (budget warnings, flags…)           | dedup key(user,type,id,key)                 |
| password_resets  | One-time reset tokens (hashed)             | token_hash char(64), expiry                 |
| announcements    | Reserved for admin bulletins (deliverable) | —                                            |

## Cardinality summary
- `users 1 ── N categories / transactions / budgets / tips / insights / notifications / password_resets`
- `categories 1 ── N transactions / budgets`; `categories 1 ── 0..N tips` (SET NULL on delete)
- Deletion of a user **cascades** to every child table (their data goes with them).