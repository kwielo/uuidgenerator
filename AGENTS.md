# AGENTS.md

Guidance for AI agents (and humans) working in this repository. Read this before
making changes. The goal is a **small, sharp codebase**: minimal surface area, high
standards, and no accidental complexity.

## What this project is

A lightweight **Symfony 6.4 micro-kernel** web app that generates UUIDs (v1 and v4).
It exposes a Twig-rendered browser UI and a plain-text HTTP API. Ramsey/Uuid does the
actual UUID work; the app is deliberately thin around it.

- **Runtime:** PHP >= 8.1 (CI runs 8.3). Extensions: `intl`, `mbstring`, `ctype`, `iconv`.
- **Framework:** Symfony 6.4 via `MicroKernelTrait` — routes are declared in code
  (`src/App/Kernel.php`), not in YAML.
- **Autoload:** PSR-4, `App\` → `src/App/`, tests `App\Tests\` → `tests/`.

## Repository map

```
src/App/
  Kernel.php               # Micro-kernel: route definitions live here
  Controller/              # Thin HTTP handlers (UI + API)
    IndexController.php     # Browser UI, bulk generation (clamped to MAX_BULK=100)
    ApiController.php       # Plain-text API endpoint
  Repository/
    UuidRepository.php      # All UUID generation + type logic (the domain core)
  views/index.html.twig     # UI template
config/                     # Bundles, services (autowiring), Twig, framework config
public/index.php            # Front controller / entrypoint
tests/                      # PHPUnit: mirrors src/App structure
```

## Architecture & design principles

Keep the architecture **light but disciplined**. The layering below is the whole point
of the project's cleanliness — do not collapse or bypass it.

- **Thin controllers, smart repository.** Controllers only translate HTTP <-> domain:
  read params, call `UuidRepository`, return a `Response`. All generation logic,
  supported-type knowledge, and validation lives in `UuidRepository`. Never inline
  UUID logic into a controller or template.
- **Single source of truth for types.** The list of supported UUID types lives once, in
  `UuidRepository::UUID_TYPES`, surfaced via `getTypes()` and dispatched via `getUuid()`.
  Adding a version means touching that one class only — the UI and API pick it up for free.
- **Constructor injection + autowiring.** Depend on collaborators through the constructor
  (`private readonly ...`), let Symfony autowire them (`config/services.yaml`). Do not use
  the service locator / `$container->get()`, and do not construct dependencies with `new`
  inside consumers.
- **Fail fast on bad input.** Invalid types throw `BadRequestHttpException` (→ HTTP 400).
  Validate at the domain boundary; don't silently coerce or return a default for garbage.
- **Bounds are enforced in code, not trusted from input.** e.g. bulk count is clamped
  with `max(1, min($bulk, MAX_BULK))`. Keep such invariants explicit and centralized.

### SOLID, applied here (concretely, not dogmatically)

- **S — Single Responsibility:** each class has one reason to change. `UuidRepository` =
  UUID domain; controllers = HTTP mapping; Twig = presentation. If a controller starts
  "knowing" how UUIDs work, move that into the repository.
- **O — Open/Closed:** extend by adding a new case to the `match` in `getUuid()` and a new
  entry in `UUID_TYPES`, not by rewriting callers. If version dispatch ever grows complex
  (many versions, per-version options), prefer a strategy/generator interface with one
  implementation per version over a sprawling `match`.
- **L — Liskov:** any future generator abstraction must return a valid, spec-conformant
  UUID string for its declared version — no surprises for callers.
- **I — Interface Segregation:** keep public methods focused (`getUuid`, `getTypes`,
  `getNil`). Don't add broad "do everything" methods; add small, intention-revealing ones.
- **D — Dependency Inversion:** controllers depend on the `UuidRepository` abstraction
  injected by the container, never on concrete framework internals. Introduce an interface
  only when there's a second implementation or a real testing need — don't add ceremony
  speculatively.

### Keep it light

- **YAGNI / no speculative generality.** Don't add layers (services, DTOs, events,
  interfaces) until a concrete need exists. This app's value is its smallness.
- **No new runtime dependencies without justification.** The dependency list is short on
  purpose. Prefer the standard library / existing deps. If you must add one, pick a
  version published a while ago (not `latest`), pin sanely, and explain why in the PR.
- **Prefer editing existing files over adding new ones.** Mirror surrounding style.

## Conventions

- **PHP style:** PER-CS ruleset via PHP-CS-Fixer, plus `strict_param`, short arrays,
  single quotes, ordered imports, no unused imports. Run the fixer — don't hand-format.
- **Types everywhere.** Full parameter and return type declarations; annotate array shapes
  with PHPDoc (`@return string[]`) where types alone are insufficient. Code must pass
  **PHPStan level 8** — no `mixed` leaks, no ignored errors without a justified baseline.
- **Immutability by default:** inject collaborators as `private readonly`; use `const` for
  fixed sets and limits (`UUID_TYPES`, `MAX_BULK`).
- **Routing:** add/adjust routes in `Kernel::configureRoutes`. Keep `requirements`
  (regex constraints) tight so bad paths 404 instead of reaching controllers.
- **API responses** are raw plain text (`new Response($string)`); the UI goes through Twig.
  Don't blur the two.

## Testing

Tests live in `tests/` mirroring `src/App/`, using PHPUnit 10. `phpunit.xml.dist` sets
`failOnRisky` and `failOnWarning` — treat warnings as failures.

- **Unit tests** (`TestCase`) for the repository: assert validity and version of generated
  UUIDs, uniqueness, the exact NIL value, and that invalid types throw.
- **Functional tests** (`WebTestCase`) for controllers/routes: status codes and response
  bodies (e.g. `/api/uuid4` returns a valid v4; `/api/invalid` → 400).
- **Add/extend tests with every behavioral change.** New UUID type ⇒ new repository and API
  test cases. Do not modify existing tests just to make them pass — fix the code, or flag
  the test as wrong in the PR.

## Local commands

```bash
composer install                 # install deps

# run locally (PHP built-in server)
APP_ENV=dev APP_SECRET=change-me php -S localhost:8000 -t public

composer test        # PHPUnit
composer phpstan     # PHPStan level 8
composer cs-check    # PHP-CS-Fixer dry-run (no changes)
composer cs-fix      # PHP-CS-Fixer apply fixes
```

Docker: `docker compose up -d` → http://localhost:8000.

## Before you commit / open a PR

Run all three quality gates locally and make them green — CI (`.github/workflows`) runs the
exact same checks on every push/PR against `master`, and will block otherwise:

```bash
composer test && composer phpstan && composer cs-check
```

- Keep changes **minimal and focused**; no drive-by refactors of unrelated code.
- Never commit secrets. `APP_SECRET` is a placeholder for local/dev only.
- Base branch is **`master`**.
