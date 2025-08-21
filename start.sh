#!/bin/bash

# Default port is 8000
process=${1:-'start'}
PORT=${2:-8000}

if [[ "$process" != "job" ]]; then
    echo "Killing any process using TCP port $PORT..."
    fuser -n tcp -k $PORT

    echo "Starting Laravel server on port $PORT..."
    php artisan serve --port=$PORT
else
    echo "Restarting Laravel queue workers..."
    php artisan queue:restart
    php artisan queue:retry all
    php artisan queue:work --timeout=0
fi
