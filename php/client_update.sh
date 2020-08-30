#!/bin/bash

# Crontab line:
# */10 * * * * /home/user/client_update.sh

curl -user test:password -X POST -H "Content-Type: application/x-www-form-urlencoded" -d
"client_ip=NAMEORIP" https://.duckdns.org/keepalive/index.php
