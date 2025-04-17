#!/bin/bash
while true; do
  ssh -o "TCPKeepAlive=no" -o "ServerAliveCountMax=0" -o "ServerAliveInterval=0" -L 8385:localhost:8384 -T fedora@bridlewood.equestria.dev
done