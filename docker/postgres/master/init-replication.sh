#!/bin/sh
set -e

# Cria um usuário só para replicação
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<EOSQL
CREATE ROLE replicator WITH REPLICATION LOGIN PASSWORD '${REPLICATION_PASSWORD}';
EOSQL

# Libera esse usuário para conexões de replicação
echo "host replication replicator all scram-sha-256" >> "$PGDATA/pg_hba.conf"