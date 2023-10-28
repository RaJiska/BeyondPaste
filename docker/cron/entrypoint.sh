#!/bin/sh
crontab -u main /crontab
crond -f -d 8