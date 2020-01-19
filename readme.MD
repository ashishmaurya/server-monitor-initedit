# Server Monitor Tool By Initedit - Opensource
This is a monitoring tool which helps you to monitor your server status
and URL( eg. you can also call your api at every day,week,month etc)

# For fresh ubuntu 17.0.4 installation (as root)

wget http://sm.initedit.com/public/download/sminstall.sh

chmod 755 sminstall.sh 

./sminstall.sh

 
# For Web based installation 


1. Download the soruce code from : https://github.com/initedit-project/server-monitor-initedit/archive/master.zip

2. Upload on you apache webserver /public_html directory

3. Go to http://yourdomain.com/install or http://youripaddress/install

4, Set cronjob "crontab -e"  for every five min  */5 * * * * /public_html/public/download/monitor.sh 

(We recommend cron job as "*/5 * * * * /home/jobs/monitor.sh" )

 

Demo Server monitor : http://sm.initedit.com


# Note:

Prerequisite : apache2, mysql-server , php, php-mysql, php-curl, curl, cronjob, zip, unzip
Keep you database  name, username and password ready
