#!/bin/bash
set -euo pipefail

# https://stackoverflow.com/a/246128/4396258
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
ROOT="${DIR}/.."

docker run -it --rm --name andrew-server \
    --publish 34823:80 \
    --volume "${PWD}/app":/var/www/html \
    php:7.4-apache