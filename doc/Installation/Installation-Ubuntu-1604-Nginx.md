source: Installation/Installation-Ubuntu-1604-Nginx.md
> NOTE: These instructions assume you are the **root** user.  If you are not, prepend `sudo` to the shell commands (the ones that aren't at `mysql>` prompts) or temporarily become a user with root privileges with `sudo -s` or `sudo -i`.

**Please note the minimum supported PHP version is 5.6.4**

## Install Required Packages ##

    apt install composer fping git graphviz imagemagick mariadb-client mariadb-server mtr-tiny nginx-full nmap php7.0-cli php7.0-curl php7.0-fpm php7.0-gd php7.0-mcrypt php7.0-mysql php7.0-snmp php7.0-xml php7.0-zip python-memcache python-mysqldb rrdtool snmp snmpd whois

#### Add librenms user

    useradd librenms -d /opt/librenms -M -r
    usermod -a -G librenms www-data

#### Install LibreNMS

    cd /opt
    composer create-project --no-dev --keep-vcs librenms/librenms librenms dev-master

## DB Server ##

#### Configure MySQL
    systemctl restart mysql
    mysql -uroot -p

> NOTE: Please change the 'password' below to something secure.
```sql
CREATE DATABASE librenms CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE USER 'librenms'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON librenms.* TO 'librenms'@'localhost';
FLUSH PRIVILEGES;
exit
```

    vim /etc/mysql/mariadb.conf.d/50-server.cnf

> NOTE: Whilst we are working on ensuring LibreNMS is compatible with MySQL strict mode, for now, please disable this after mysql is installed.

Within the `[mysqld]` section please add:

```bash
innodb_file_per_table=1
sql-mode=""
lower_case_table_names=0
```
    systemctl restart mysql

## Web Server ##

### Configure and Start PHP-FPM
Ensure date.timezone is set in php.ini to your preferred time zone.  See http://php.net/manual/en/timezones.php for a list of supported timezones.  Valid examples are: "America/New_York", "Australia/Brisbane", "Etc/UTC".

    vim /etc/php/7.0/fpm/php.ini
    vim /etc/php/7.0/cli/php.ini

    phpenmod mcrypt
    systemctl restart php7.0-fpm

#### Configure NGINX

    vim /etc/nginx/conf.d/librenms.conf

Add the following config, edit `server_name` as required:

```nginx
server {
 listen      80;
 server_name librenms.example.com;
 root        /opt/librenms/html;
 index       index.php;

 charset utf-8;
 gzip on;
 gzip_types text/css application/javascript text/javascript application/x-javascript image/svg+xml text/plain text/xsd text/xsl text/xml image/x-icon;
 location / {
  try_files $uri $uri/ /index.php?$query_string;
 }
 location /api/v0 {
  try_files $uri $uri/ /api_v0.php?$query_string;
 }
 location ~ \.php {
  include fastcgi.conf;
  fastcgi_split_path_info ^(.+\.php)(/.+)$;
  fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
 }
 location ~ /\.ht {
  deny all;
 }
}
```

    rm /etc/nginx/sites-enabled/default
    systemctl restart nginx

#### Configure snmpd

    cp /opt/librenms/snmpd.conf.example /etc/snmp/snmpd.conf
    vim /etc/snmp/snmpd.conf

Edit the text which says `RANDOMSTRINGGOESHERE` and set your own community string.

    curl -o /usr/bin/distro https://raw.githubusercontent.com/librenms/librenms-agent/master/snmp/distro
    chmod +x /usr/bin/distro
    systemctl restart snmpd

### Cron job

    cp /opt/librenms/librenms.nonroot.cron /etc/cron.d/librenms

#### Copy logrotate config

LibreNMS keeps logs in `/opt/librenms/logs`. Over time these can become large and be rotated out.  To rotate out the old logs you can use the provided logrotate config file:

    cp /opt/librenms/misc/librenms.logrotate /etc/logrotate.d/librenms

### Set permissions

    chown -R librenms:librenms /opt/librenms
    setfacl -d -m g::rwx /opt/librenms/rrd /opt/librenms/logs
    setfacl -R -m g::rwx /opt/librenms/rrd /opt/librenms/logs

## Web installer ##

Now head to the web installer and follow the on-screen instructions.

    http://librenms.example.com/install.php

### Final steps

That's it!  You now should be able to log in to http://librenms.example.com/.  Please note that we have not covered HTTPS setup in this example, so your LibreNMS install is not secure by default.  Please do not expose it to the public Internet unless you have configured HTTPS and taken appropriate web server hardening steps.

#### Add the first device

We now suggest that you add localhost as your first device from within the WebUI.

#### Troubleshooting

If you ever have issues with your install, run validate.php as root in the librenms directory:

    cd /opt/librenms
    ./validate.php

There are various options for getting help listed on the LibreNMS web site: https://www.librenms.org/#support

### What next?

Now that you've installed LibreNMS, we'd suggest that you have a read of a few other docs to get you going:

 - [Performance tuning](http://docs.librenms.org/Support/Performance)
 - [Alerting](http://docs.librenms.org/Extensions/Alerting/)
 - [Device Groups](http://docs.librenms.org/Extensions/Device-Groups/)
 - [Auto discovery](http://docs.librenms.org/Extensions/Auto-Discovery/)

### Closing

We hope you enjoy using LibreNMS. If you do, it would be great if you would consider opting into the stats system we have, please see [this page](http://docs.librenms.org/General/Callback-Stats-and-Privacy/) on what it is and how to enable it.

If you would like to help make LibreNMS better there are [many ways to help](http://docs.librenms.org/Support/FAQ/#what-can-i-do-to-help). You can also [back LibreNMS on Open Collective](https://t.libren.ms/donations).
