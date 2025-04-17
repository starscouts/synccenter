#!/bin/bash
while true; do
  ssh -o "TCPKeepAlive=no" -o "ServerAliveCountMax=0" -o "ServerAliveInterval=0" -p 52274 -L 8390:localhost:8384 -T root@everfree.equestria.dev
done