echo  -e "\n1. Please move the file from /var/www/html \n2. apache2.conf file will be repalced(backup file will be created in /etc/apache2/apache2.conf.backup"
echo -e "3. Remmber the mysql password \n4. Cron job will be created with root user(/var/spool/cron/crontabs/root)"

read wait1

echo "Installing Server Monitor"

echo "This packages will be installed apache2,php,php-mysql,php,php-curl,mysql,zip,unzip"

apt-get update
apt-get install apache2 php mysql-server curl php-mysql php-curl zip unzip

sleep 1

echo "Creating the mysql database"

read -p "Enter database name : " db

echo "create database $db" | mysql -u root -p

echo "Downloading the server monitor file..."

sleep 2

wget https://github.com/initedit-project/server-monitor-initedit/archive/master.zip

mv master.zip /var/www/html/

cd /var/www/html/

unzip master.zip

cd server-monitor-initedit-master

mv * ../

mv htaccess_sample.txt ../

cd ..

chown -R www-data:www-data /var/www/html/*

chown www-data:www-data /var/www/html/htaccess_sample.txt

rm index.html

rm -rf server-monitor-initedit-master master.zip



cd /var/www/html/public/download/

cp /etc/apache2/apache2.conf /etc/apache2/apache2.conf.backup

mv /var/www/html/public/download/apache2.conf /etc/apache2/

cd /var/www/html/

mv htaccess_sample.txt .htaccess

a2enmod rewrite

service apache2 restart

echo "*/5 * * * * /home/jobs/monitor.sh" > cronjobs.txt
echo "0 1 * * 1  /home/jobs/smlogremove.sh" >> cronjobs.txt

crontab cronjobs.txt

rm -rf cronjobs.txt

service cron restart

sleep 2

service cron reload

mkdir /home/jobs
cd /home/jobs

mv /var/www/html/public/download/monitor.sh /home/jobs
mv /var/www/html/public/download/smlogremove.sh /home/jobs

chmod 755 monitor.sh
chmod 755 smlogremove.sh

echo -e "\n\n --------------------Installation completed------------------------"
echo -e "\n"
echo "Keep your database/username/password ready"
echo -e "\n\n"
echo "Database name : $db"
echo "Database username : root"
echo -e "\n\n"
echo "Go to http://localhost/install or http://yourip/install"
echo -e "\n\n"
