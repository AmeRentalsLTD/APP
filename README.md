# AME Rentals Platform

[![CI](https://github.com/AmeRentalsLTD/APP/actions/workflows/ci.yml/badge.svg)](https://github.com/AmeRentalsLTD/APP/actions/workflows/ci.yml)
[![CodeQL](https://github.com/AmeRentalsLTD/APP/actions/workflows/codeql.yml/badge.svg)](https://github.com/AmeRentalsLTD/APP/actions/workflows/codeql.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Getting Started

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Create your environment file and generate an application key:

   ```bash
   cp .env.example .env # create your own if the example file is not available
   php artisan key:generate
   ```

3. For local development this project uses SQLite by default. Ensure the database file exists:

   ```bash
   touch database/database.sqlite
   ```

4. Run the database migrations:

   ```bash
   php artisan migrate
   ```

5. (Optional) Install the JavaScript dependencies for the Filament admin panel assets:

   ```bash
   npm install
   npm run build
   ```

### Finance module quickstart

Seed the finance demo data and useful shortcuts:

```bash
php artisan finance:demo
```

This command will populate sample customers, vehicles, rentals, invoices, deposits, payments, and expenses for AME Rentals Ltd. It will also remind you that the Filament admin panel lives under `/admin` and that the new finance reports are grouped within the **Finance reports** navigation section.

To keep recurring invoices, overdue statuses, and deposit releases up to date, schedule the following cron entry on your host (this triggers Laravel's scheduler every minute):

```
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

The scheduler will cascade into three queued jobs: `GenerateRecurringInvoicesJob`, `MarkOverdueInvoicesJob`, and `DepositReleaseEligibilityJob`.

## API Docs

Swagger UI is available at [`/api/docs`](http://localhost/api/docs), powered by [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger). The canonical OpenAPI definition lives at [`docs/openapi.yaml`](docs/openapi.yaml).

## API Overview

The first iteration of the fleet rental platform exposes a versioned REST API located under `/api/v1`.

| Resource            | Endpoints                                                                 |
|---------------------|---------------------------------------------------------------------------|
| Vehicles            | `GET/POST /api/v1/vehicles`, `GET/PATCH/DELETE /api/v1/vehicles/{id}`     |
| Customers           | `GET/POST /api/v1/customers`, `GET/PATCH/DELETE /api/v1/customers/{id}`   |
| Rental Agreements   | `GET/POST /api/v1/rental-agreements`, `GET/PATCH/DELETE /api/v1/rental-agreements/{id}` |

### Validation highlights

- Vehicle registration numbers are normalised to uppercase and must be unique.
- Customers support multiple organisation types (`individual`, `sole_trader`, `partnership`, `ltd`, `llp`).
- Rental agreements enforce compatible options for billing cycles, mileage policies, and payment cadence.

Each endpoint returns a JSON:API-like payload with the resource stored under the `data` key. Pagination metadata is included automatically when listing resources.

## Vehicle compliance data

When GOV.UK credentials are supplied the application can automatically hydrate a vehicle's MOT expiry and road tax due dates by querying the DVSA and DVLA trade APIs. Configure the following environment variables (also documented in `.env.example`):

- `GOV_UK_DVLA_API_KEY` (and optional `GOV_UK_DVLA_BASE_URL`)
- `GOV_UK_DVSA_API_KEY` (and optional `GOV_UK_DVSA_BASE_URL`)

Newly created vehicles – or existing records updated without explicitly providing compliance dates – will be refreshed automatically. You can also run the scheduled-friendly artisan command to backfill the entire fleet:

```bash
php artisan vehicles:sync-compliance
```

## Development

Common tooling commands:

- `composer format`
- `composer analyse`
- `php artisan test --parallel`

## Testing

Run the feature and unit test suite with:

```bash
php artisan test
```

These tests cover the core CRUD flows for vehicles, customers, and rental agreements that underpin Phase 1 of the roadmap.
