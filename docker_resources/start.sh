#!/bin/bash
service nginx start
service mysql start
service php5-fpm start
service sphinxsearch start
cron -f
