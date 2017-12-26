work_path=$(dirname $(readlink -f $0))
nohup ${work_path}/controller/console/console_push.sh >> ${work_path}/library/logs/console_push.log 2>&1 &