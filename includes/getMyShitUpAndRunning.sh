#!/bin/bash

# ------------------------------------
#
# DO NOT modify this file.
#
# It is made to work perfectly with
# Synccenter, stuff WILL break if you
# modify it in unexpected ways.
# You have been warned.
#
# ------------------------------------

read -p "Enter the port number Raindrops has given you: " port
echo "#!/bin/bash" > ~/.tunnel.sh
echo "" >> ~/.tunnel.sh
echo "# ------------------------------------" >> ~/.tunnel.sh
echo "#" >> ~/.tunnel.sh
echo "# DO NOT modify this file." >> ~/.tunnel.sh
echo "#" >> ~/.tunnel.sh
echo "# It is made to work perfectly with" >> ~/.tunnel.sh
echo "# Synccenter, stuff WILL break if you" >> ~/.tunnel.sh
echo "# modify it in unexpected ways." >> ~/.tunnel.sh
echo "# You have been warned." >> ~/.tunnel.sh
echo "#" >> ~/.tunnel.sh
echo "# ------------------------------------" >> ~/.tunnel.sh
echo "" >> ~/.tunnel.sh
echo "while true; do" >> ~/.tunnel.sh
echo "  ssh -o \"TCPKeepAlive=no\" -o \"ServerAliveCountMax=0\" -o \"ServerAliveInterval=0\" -p 2223 -R $port:localhost:8384 -T root@maretimebay.equestria.dev" >> ~/.tunnel.sh
echo "done" >> ~/.tunnel.sh
chmod +x ~/.tunnel.sh
crontab -l > ~/.crontab
echo '@reboot screen -dmS tunnel bash -c "cd ~; chmod +x ~/.tunnel.sh; ~/.tunnel.sh"' >> ~/.crontab
crontab ~/.crontab
rm ~/.crontab
echo "Installed successfully, please restart your computer so the tunnel can run."