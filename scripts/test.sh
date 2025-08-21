#!/usr/bin/env bash
set -euo pipefail

# Simple interactive test runner for Docker Compose
# Defaults:
DC_CMD=${DC_CMD:-docker-compose}
SERVICE=${SERVICE:-web}

print_header() {
  echo "== Sweetwater Test Runner =="
  echo "Service: $SERVICE (via $DC_CMD)"
  echo
}

ensure_containers() {
  # Ensure db and web are up
  $DC_CMD up -d db web >/dev/null
}

ensure_dependencies() {
  # If vendor isn't present in the container, install it
  if ! $DC_CMD exec -T "$SERVICE" test -f vendor/autoload.php >/dev/null 2>&1; then
    echo "Installing PHP dependencies in container..."
    $DC_CMD exec "$SERVICE" composer install --no-interaction --prefer-dist --optimize-autoloader
  fi
}

run_phpunit() {
  # Pass-through any remaining args to phpunit
  $DC_CMD exec "$SERVICE" vendor/bin/phpunit "$@"
}

run_coverage() {
  # Requires Xdebug or PCOV in the container
  $DC_CMD exec "$SERVICE" composer test-coverage
}

show_menu() {
  cat <<'MENU'
Select what to run:
  1) All tests
  2) Unit tests only
  3) Integration tests only
  4) Coverage (if Xdebug/PCOV installed)
  h) Help
  q) Quit
MENU
}

usage() {
  cat <<'USAGE'
Usage:
  scripts/test.sh [all|unit|integration|coverage|help] [-- phpunit-args]
Examples:
  scripts/test.sh                 # interactive menu
  scripts/test.sh all             # run all tests
  scripts/test.sh unit            # run unit tests
  scripts/test.sh integration     # run integration tests
  scripts/test.sh coverage        # run coverage (needs Xdebug/PCOV)
  scripts/test.sh all -- -v       # pass -v to phpunit
USAGE
}

main() {
  print_header
  ensure_containers
  ensure_dependencies

  subcmd="${1:-}"
  shift || true

  case "$subcmd" in
    all|"")
      if [ -z "$subcmd" ]; then
        show_menu
        read -r -p "> " choice
        case "$choice" in
          1|all|A|a|"") subcmd=all ;;
          2|unit|U|u)     subcmd=unit ;;
          3|integration|I|i) subcmd=integration ;;
          4|coverage|C|c) subcmd=coverage ;;
          h|H|help) usage; exit 0 ;;
          q|Q) echo "Bye"; exit 0 ;;
          *) echo "Unknown choice: $choice"; usage; exit 1 ;;
        esac
      fi
      case "$subcmd" in
        all) run_phpunit "$@" ;;
        unit) run_phpunit tests/Unit "$@" ;;
        integration) run_phpunit tests/Integration "$@" ;;
        coverage) run_coverage ;;
      esac
      ;;
    unit)
      run_phpunit tests/Unit "$@"
      ;;
    integration)
      run_phpunit tests/Integration "$@"
      ;;
    coverage)
      run_coverage
      ;;
    help|-h|--help)
      usage
      ;;
    *)
      echo "Unknown command: $subcmd" >&2
      usage
      exit 1
      ;;
  esac
}

main "$@"
