# API README

This folder contains a lightweight PHP API skeleton without Composer, organized by feature. Below is an overview of the directory structure and the main files.

## Folder Structure

-   `config/`

    -   `config.php`: Central place for global configuration values (e.g., app name, debug flags).
    -   `database.php`: Database connection bootstrap or placeholder for future DB setup.
    -   `env.php`: Loads environment variables or sets defaults (e.g., base URL, environment mode).

-   `src/`

    -   `Controllers/`
        -   `HomeController.php`: Example controller returning a basic response for the root or health endpoint.
        -   `ShowStatusController.php`: Controller for the Show Status feature; handles incoming requests and delegates to services.
    -   `Helpers/`
        -   `autoload.php`: Custom PSR‑4–style autoloader for the `App\` namespace and a small route matcher function.
        -   `response.php`: Small helper to send JSON/HTML responses with status codes and headers.
    -   `Middlewares/`: Placeholder for request middlewares (e.g., auth, CORS, input validation).
    -   `migrations/`
        -   `createTableShowStatus.sql`: SQL migration example for creating the table used by the Show Status feature.
    -   `Repositories/`
        -   `ShowStatusRepository.php`: Data access layer for Show Status; abstracts persistence (DB queries, storage).
    -   `Routes/`
        -   `routes.php`: Defines the route table: HTTP method, path, and handler mapping.
    -   `Services/`
        -   `ShowStatusService.php`: Business logic for Show Status; coordinates between controller and repository.

-   `index.php`
    -   The front controller. Loads config and autoloader, reads the incoming request (`$_SERVER`), matches it against defined routes (`src/Routes/routes.php`), and dispatches to the appropriate controller.

## Request Flow (High Level)

1. `index.php` initializes configuration and autoloading.
2. The request method and URI are matched using `matchRoute()` from `src/Helpers/autoload.php` against entries in `src/Routes/routes.php`.
3. The matched handler (controller class/method) is invoked.
4. Controllers call `Services` for business logic; services use `Repositories` for data access.
5. Responses are formatted via `src/Helpers/response.php`.

## Key Utilities

-   Autoloading: `src/Helpers/autoload.php` registers a simple autoloader for classes under the `App\` namespace, mapping to files under `src/`.
-   Route matching: The helper converts path parameters like `/items/{id}` into a regex, extracts parameters, and returns the handler + params.
-   Response helper: Send JSON or HTML responses consistently (status code, headers, body).

## Adding a New Feature (Example)

1. Create a service in `src/Services/` (e.g., `BooksService.php`).
2. Create a repository in `src/Repositories/` for data access.
3. Create a controller in `src/Controllers/` (e.g., `BooksController.php`).
4. Register routes in `src/Routes/routes.php` (e.g., `['GET', '/books', [BooksController::class, 'index']]`).
5. If needed, add a migration under `src/migrations/` and update `database.php`.

## Environment & Config

-   Use `config/env.php` to set environment‑specific values (e.g., `APP_ENV`, `BASE_URL`).
-   `config/database.php` can initialize a PDO connection; currently serves as a placeholder if not using a DB yet.

## Notes

-   This API is intentionally minimal and suitable for local development or coursework demos.
-   No Composer is used; autoloading is handled manually.
-   Consider adding middlewares (auth, rate limiting) in `src/Middlewares/` as the project grows.
