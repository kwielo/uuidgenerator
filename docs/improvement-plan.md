# kwielo/uuidgenerator Improvement Plan

## Goals

- Modernize the app from Symfony 4.x to Symfony 6.4 LTS
- Remove security risks and stale infrastructure defaults
- Add a baseline for testing, linting, and release hygiene
- Expand UUID generation and API capabilities over time

## Phase 1 — Security & Stability

1. Move the Symfony secret to an environment variable
2. Validate and cap the bulk parameter to prevent abuse
3. Replace the Docker image stack with maintained official images
4. Add basic security headers to nginx

## Phase 2 — Framework Upgrade

1. Upgrade to Symfony 6.4 LTS
2. Replace deprecated routing APIs
3. Introduce dependency injection for repositories and controllers
4. Update the runtime bootstrap to Symfony's modern entrypoint

## Phase 3 — Quality & Automation

1. Add PHPUnit coverage for controllers and repositories
2. Add static analysis and code style checks
3. Introduce CI to run tests and checks automatically
4. Document setup and usage in the repository README

## Phase 4 — Product Improvements

1. Add more UUID versions, including modern time-ordered variants
2. Improve the API to return structured JSON responses
3. Enhance the UI copy flow and accessibility
4. Consider removing the forked UUID library if upstream is sufficient
