# UUID Generator

A lightweight Symfony 6.4 web application for generating UUIDs (v1 and v4). Provides both a browser UI and a plain-text API.

## Requirements

- PHP >= 8.1 (with `intl`, `mbstring`, `ctype`, `iconv` extensions)
- Composer

## Setup

```bash
composer install
```

### Running locally (PHP built-in server)

```bash
APP_ENV=dev APP_SECRET=change-me php -S localhost:8000 -t public
```

### Running with Docker

```bash
docker compose up -d
# App available at http://localhost:8000
```

## Usage

### Web UI

Open `http://localhost:8000` in a browser. Use the dropdown to choose UUID version and the slider to generate multiple UUIDs at once (up to 100).

### API

```
GET /api/uuid4   # returns a single UUID v4 as plain text
GET /api/uuid1   # returns a single UUID v1 as plain text
```

## Development

### Tests

```bash
composer test
```

### Static analysis (PHPStan level 8)

```bash
composer phpstan
```

### Code style (PHP-CS-Fixer, PER-CS ruleset)

```bash
composer cs-check   # dry-run check
composer cs-fix     # auto-fix
```

### CI

Tests, PHPStan, and PHP-CS-Fixer run automatically on every push and pull request via GitHub Actions.

## License

MIT
