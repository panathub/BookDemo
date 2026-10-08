# Dev image

PHP 8.3 CLI with gd, zip, intl, pdo_mysql, pdo_sqlite and composer. The repo is mounted at `/app`.

Install dependencies and run the tests:

    docker compose -f docker/compose.dev.yml run --rm app composer install --no-interaction
    docker compose -f docker/compose.dev.yml run --rm app php artisan test

`php artisan test` needs a database. Start `db` first with `docker compose -f docker/compose.dev.yml up -d db`, or uncomment the sqlite lines in `phpunit.xml`, which PR-1 does.

Serve the app on http://localhost:8000:

    docker compose -f docker/compose.dev.yml up

The `db` service is a throwaway MySQL 8 on host port 3307 with root password `root`. It is not for real data.
