#!/bin/bash
work_path=$(dirname $(readlink -f $0))
(
    until /usr/bin/php ${work_path}/push.php; do
        echo "Carbon Forum push service crashed with exit code $?.  restarting... " >&2
        sleep 3
    done
) &