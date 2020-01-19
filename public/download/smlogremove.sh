tail -n 2016 /var/log/monitor.log > /var/log/monitor1.log

cat /var/log/monitor1.log > /var/log/monitor.log

rm monitor1.log