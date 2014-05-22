yum -y install php php-mbstring php-pear php-xml php-pdo mysql-server php-mysql
/sbin/chkconfig --level 2345 mysqld on
/etc/rc.d/init.d/mysqld start
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
/usr/local/bin/composer --global install
if [ ! -f /etc/php.ini.org ]; then
  sudo cp /etc/php.ini /etc/php.ini.org
fi
sudo cp /etc/php.ini.org /etc/php.ini
sudo cat << EOF >> /etc/php.ini
error_log = /var/log/php.log
mbstring.language = Japanese
mbstring.internal_encording = UTF-8
mbstring.http_inpup = auto
expose_php = Off
date.timezone = Asia/Tokyo
EOF

