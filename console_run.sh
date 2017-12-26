CURRENT_PATH=$(dirname $(readlink -f $0))
SERVICE_PUSH_PATH=${CURRENT_PATH}/controller/console/console_push.sh
LOG_PUSH_PATH=${CURRENT_PATH}/library/logs
LOG_PUSH_FILE=${LOG_PUSH_PATH}/console_push.log
mkdir -p ${LOG_PATH}
touch ${LOG_PUSH_FILE}
chmod +x ${SERVICE_PUSH_PATH}
nohup ${SERVICE_PUSH_PATH} >> ${LOG_PUSH_FILE} 2>&1 &