#!/bin/bash
#!/usr/bin/php
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
export DISPLAY=:0.0
NOW=$(date +"%Y%m%d")
OUTPUT_FILENAME="/root/tms_logfiles/runday_$NOW.log"
php /var/www/html/TimeManagementSystem/index.php Backgroundjobs dailyremind > "$OUTPUT_FILENAME"

