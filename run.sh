#!/bin/bash -e

RED_COLOR='\033[0;31m'
GREEN_COLOR='\033[32m'
BLUE_COLOR='\033[34m'
NO_COLOR='\033[0m'

IP=127.0.0.1

APP_CONTAINER=app

DOCKER_FILE="docker/docker-compose.dev.local.yaml"

ENV_FILE=".env"

DOCKER_MOUNT="${@:2}"
if [ -z "${DOCKER_MOUNT}" ] 
then
    DOCKER_MOUNT="up"
fi

add_hosts() {
    HOSTS=$(get_var VIRTUAL_HOST)
    for HOST in $(echo $HOSTS | tr "," "\n")
    do
        if ! grep "${IP} ${HOST}" /etc/hosts > /dev/null
        then
            ESCAPED_HOST_DOMAIN=$(echo ${HOST} | sed -e 's/\./\\./g')
            sudo sed -i -e "/${ESCAPED_HOST_DOMAIN}/d" /etc/hosts
            echo "${IP} ${HOST}" | sudo tee -a /etc/hosts
        fi
    done
}

get_var() {
    VAR=$(grep $1 .env | xargs)
    echo ${VAR#*=}
}

exec_in_container() {
    docker-compose -f ${DOCKER_FILE} exec ${APP_CONTAINER} "${@:1}"
}

export COMPOSE_PROJECT_NAME=$(get_var COMPOSE_PROJECT_NAME)

case "$1" in
    init)
        add_hosts
    ;;
    dev)
        docker-sync-stack start
    ;;
    clean)
        docker-sync-stack clean
    ;;
    build)

        docker-compose -f ${DOCKER_FILE} build --no-cache
    ;;
    force-stop)
        docker-compose -f ${DOCKER_FILE} stop
    ;;
    ssh)
        exec_in_container bash
    ;;
    code-analyze)
        exec_in_container sh -c "bin/phing analyze"
    ;;
    code-cleanup)
        exec_in_container sh -c "bin/phing cleanup"
    ;;
esac