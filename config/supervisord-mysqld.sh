#!/usr/bin/env bash
[program:mysqld]
command=/start-mysqld.sh
numprocs=1
autostart=true
autorestart=true