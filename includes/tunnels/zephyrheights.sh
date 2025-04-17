#!/bin/bash
while true; do
  ssh -o "TCPKeepAlive=no" -o "ServerAliveCountMax=0" -o "ServerAliveInterval=0" -p 2222 -L 8386:localhost:8384 -T root@zephyrheights.equestria.dev
done