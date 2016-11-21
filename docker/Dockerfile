FROM ubuntu:xenial
MAINTAINER Henry Lo <henryzlo@gmail.com>

RUN apt-get update
RUN `echo "mysql-server mysql-server/root_password password NEWMYSQLROOTPASSWORD" | debconf-set-selections`
RUN `echo "mysql-server mysql-server/root_password_again password NEWMYSQLROOTPASSWORD" | debconf-set-selections`
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y apache2 mysql-server libapache2-mod-php php-mysql php-xml

# Set up Apache
COPY docker/configure_apache.sh /configure_apache.sh
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN chmod 755 /configure_apache.sh
RUN /configure_apache.sh

# Initialize codebase
RUN rm /var/www/html/index.html
COPY . /var/www/html
RUN `sed -i -e 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/7.0/apache2/php.ini`

# Set up MySQL
COPY docker/configure_mysql.sh /configure_mysql.sh
RUN chmod 755 /configure_mysql.sh
RUN /configure_mysql.sh

EXPOSE 80

# Initialization script
COPY docker/initialize.sh /initialize.sh
RUN chmod 755 /initialize.sh
CMD /initialize.sh