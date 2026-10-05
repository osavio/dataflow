#!/bin/sh
set -e

# Só clona o primary se esta réplica ainda não tem dados
if [ ! -s "$PGDATA/PG_VERSION" ]; then
  echo "Aguardando o primary ficar pronto..."
  until pg_isready -h db-primary -p 5432 -U "$POSTGRES_USER"; do
    sleep 2
  done

  mkdir -p "$PGDATA"
  chown postgres:postgres "$PGDATA"
  chmod 700 "$PGDATA"

  echo "Clonando o primary com pg_basebackup..."
  su-exec postgres env PGPASSWORD="$REPLICATION_PASSWORD" \
    pg_basebackup -h db-primary -U replicator -D "$PGDATA" -Fp -Xs -P -R
fi

# Sobe o Postgres como réplica (o -R criou o standby.signal)
exec docker-entrypoint.sh postgres -c hot_standby=on