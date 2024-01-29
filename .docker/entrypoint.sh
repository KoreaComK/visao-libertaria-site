#!/bin/env bash
set -e

if [ ! -f ".env" ]; then
    cp env.docker .env
fi

composer install

php spark serve --host 0.0.0.0 --port 80
