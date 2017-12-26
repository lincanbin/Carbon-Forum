CURRENT_PATH=$(dirname $(readlink -f $0))
SERVICE_PUSH_PATH=${CURRENT_PATH}/controller/console/console_push.sh
LOG_PATH=${CURRENT_PATH}/library/logs/console_push.log
nohup ${SERVICE_PUSH_PATH} >> ${LOG_PATH} 2>&1 &