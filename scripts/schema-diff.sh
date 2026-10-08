#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."
export MSYS_NO_PATHCONV=1
: "${COMPOSE_FILE:=docker/compose.dev.yml}"
export COMPOSE_FILE
LIVE_HOST=${LIVE_HOST:-host.docker.internal}
LIVE_PORT=${LIVE_PORT:-3306}
LIVE_USER=${LIVE_USER:-root}
LIVE_DB=${LIVE_DB:-noblemee_booksmeet}

declare -A ONLY_IN_MIGRATIONS=(
  [accessories]="AccessoriesController and the Accessories model use it; live never created it"
  [failed_jobs]="live records its migration as run but the table was dropped"
)

docker compose up -d --wait db >/dev/null
db_secret=$(docker compose exec -T db printenv MYSQL_ROOT_PASSWORD)
db_name=$(docker compose exec -T db printenv MYSQL_DATABASE)

docker compose run --rm -T \
  -e DB_CONNECTION=mysql -e DB_HOST=db -e DB_PORT=3306 \
  -e DB_DATABASE="$db_name" -e DB_USERNAME=root -e DB_PASSWORD="$db_secret" \
  app php artisan migrate:fresh --force >/dev/null

fresh() { docker compose exec -T -e MYSQL_PWD="$db_secret" db mysql -uroot -N "$db_name" -e "SHOW $1" </dev/null; }
live() {
  docker run --rm --add-host host.docker.internal:host-gateway mysql:8 \
    mysql -h "$LIVE_HOST" -P "$LIVE_PORT" -u "$LIVE_USER" -N "$LIVE_DB" -e "SHOW $1" </dev/null
}

normalize() {
  sed -E \
    -e 's/ AUTO_INCREMENT=[0-9]+//' \
    -e 's/\b(tinyint|smallint|mediumint|int|bigint)\([0-9]+\)/\1/g' \
    -e "s/ DEFAULT '(-?[0-9]+)'/ DEFAULT \1/g" \
    -e 's/ DEFAULT NULL//g' \
    -e 's/ COLLATE=utf8mb3_general_ci$//' \
    -e 's/,$//' \
    -e '/^  KEY /s/\(`([^`]+)`\([0-9]+\)\)/(`\1`)/'
}

# MySQL 8 repeats the table collation on each column, MariaDB omits it, and the two
# servers order constraints differently.
canonicalize() {
  awk '
    { line[++n] = $0 }
    END {
      if (match(line[n], / COLLATE=[^ ]+/)) coll = " COLLATE " substr(line[n], RSTART + 9, RLENGTH - 9)
      for (i = 1; i <= n; i++) {
        if (coll != "") gsub(coll, "", line[i])
        if (line[i] ~ /^  CONSTRAINT/) c[++m] = line[i]
        else if (i == n) { asort(c); for (j = 1; j <= m; j++) print c[j]; print line[i] }
        else print line[i]
      }
    }'
}

show_create() { "$1" "CREATE TABLE \`$2\`\G" | sed '1,2d' | normalize | canonicalize; }

tmp=$(mktemp -d)
trap 'rm -rf "$tmp"' EXIT
fresh TABLES | sort >"$tmp/fresh.tables"
live TABLES | sort >"$tmp/live.tables"

status=0
while read -r t; do
  if [[ -n ${ONLY_IN_MIGRATIONS[$t]:-} ]]; then
    echo "only in migrations: $t (${ONLY_IN_MIGRATIONS[$t]})"
  else
    echo "only in migrations: $t"
    status=1
  fi
done < <(comm -23 "$tmp/fresh.tables" "$tmp/live.tables")

while read -r t; do
  echo "only in live: $t"
  status=1
done < <(comm -13 "$tmp/fresh.tables" "$tmp/live.tables")

while read -r t; do
  show_create fresh "$t" >"$tmp/$t.fresh"
  show_create live "$t" >"$tmp/$t.live"
  diff -u --label "live/$t" --label "migrations/$t" "$tmp/$t.live" "$tmp/$t.fresh" || status=1
done < <(comm -12 "$tmp/fresh.tables" "$tmp/live.tables")

if [[ $status -eq 0 ]]; then
  echo "schema matches"
fi
exit $status
