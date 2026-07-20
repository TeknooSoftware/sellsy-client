# Sellsy Client Technical Reference

A technical overview of the `sellsy-client` architecture, patterns, and development workflow.

## Core Architecture & Lifecycle

The library follows a decoupled architecture to transform Sellsy API interactions into a fluent, object-oriented
experience.

### Request Lifecycle

1. **Entry Point (`Teknoo\Sellsy\Sellsy`)**: The main facade. It manages configuration (base URL, credentials) and
   provides access to various API resource modules (e.g., `Infos`, `AccountPrefs`).
2. **Client Layer (`Teknoo\Sellsy\Client\Client`)**: Handles the orchestration of requests. It uses a **Transport** to
   execute the actual network operation.
3. **Method Layer (`Teknoo\Sellsy\Method\Method`)**: Each API endpoint is represented by a `Method` class. This
   encapsulates the specific endpoint path, the HTTP method (GET, POST, etc.), and the expected data structure.
4. **Transport Layer (`Teknoo\Sellsy\Transport\TransportInterface`)**: An abstraction layer for HTTP clients. This
   allows the library to be transport-agnostic (e.g., `Guzzle6` or `HttpPlug`).
5. **Result Layer (`Teknoo\Sellsy\Client\Result`)**: Post-request, the raw response is transformed into a `Result`
   object. It uses `voku/arrayy` to allow property-access to nested JSON data (e.g., `$result->data->id`).

## Namespace Map

| Namespace                   | Responsibility                                                   |
|:----------------------------|:-----------------------------------------------------------------|
| `Teknoo\Sellsy`             | Core entry point and orchestration.                              |
| `Teknoo\Sellsy\Client`      | Request execution, result mapping, and base exceptions.          |
| `Teknoo\Sellsy\Method`      | Implementation of specific API resource calls.                   |
| `Teknoo\Sellsy\Collection`  | Handling collections of resources.                               |
| `Teknoo\Sellsy\Transport`   | Interface definitions for HTTP transport bridges.                |
| `Teknoo\Sellsy\Exception`   | Base exception hierarchy.                                        |
| `Teknoo\Sellsy\Definitions` | (Internal/Tooling) Definitions used by CLI tools for validation. |

## Key Design Patterns

- **Strategy Pattern**: Used in the `Transport` layer to allow swapping HTTP clients (Guzzle, etc.) without changing
  core logic.
- **Data Mapper / Wrapper**: The `Result` class wraps associative arrays into objects with dynamic property access to
  simplify complex API JSON responses.
- **Exception Mapping**: The client intercepts raw API error responses and maps them to specific, typed PHP exceptions
  (e.g., `ParameterMissingException`, `NotAllowedException`) to provide semantic error handling.

## Technical Specifications

- **PHP Version**: Minimum `8.1`.
- **Standards**: PSR-7 (HTTP Message Interface), PSR-4 (Autoloading), and PSR-12 (Coding Style).
- **Core Dependencies**:
    - `voku/arrayy`: For advanced array-to-object mapping.
    - `psr/http-message`: For standardized request/response interfaces.
    - `teknoo/immutable`: For immutable data structures.

## Development & QA

### Validation Commands

All quality checks are orchestrated via `make`.

- **`make test`**: Executes the PHPUnit test suite.
- **`make qa`**: Runs the full suite of static analysis and linting:
    - `lint`: Syntax validation.
    - `phpstan`: Static analysis for type safety.
    - `phpcs`: PSR-12 compliance check.
    - `audit`: Security audit of dependencies.
- **`make checkmethods`**: A specialized tool that validates the implementation against the remote Sellsy API
  documentation to ensure consistency.

### Cleanup

- `make clean`: Removes the `vendor` directory.
