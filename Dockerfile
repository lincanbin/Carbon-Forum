FROM ubuntu:14.04

#RUN echo "nameserver 192.168.99.1" > /etc/resolv.conf ; \
RUN \
    sed -i "s#archive.ubuntu.com#cn.archive.ubuntu.com#" /etc/apt/sources.list ; \
    echo 'mysql-server mysql-server/root_password password kf_kf_kf' | debconf-set-selections  ; \
    echo 'mysql-server mysql-server/root_password_again password kf_kf_kf' | debconf-set-selections ;\
    apt-get update && apt-get install -y nginx php5-fpm php5-mysqlnd php5-curl php5-gd mysql-server mysql-client ; \
    service mysql start && echo 'create database knowledge;create user klg_u@localhost identified by "magic*docker";grant all privileges on knowledge.* to klg_u@localhost '| mysql -uroot -p'kf_kf_kf' ; \
    useradd -d /var/www/carbon_forum web; \
    mkdir -p /var/www/carbon_forum

# cd /tmp;\
# wget https://pecl.php.net/get/sphinx-1.3.3.tgz ;\
# tar xf sphinx-1.3.3.tgz && cd sphinx-1.3.3 ; \
# phpize && ./configure && make && make install;\
# rm /tmp/*; \

COPY docker_resources/sphinx.so /usr/lib/php5/20121212/

#RUN echo "nameserver 192.168.99.1" > /etc/resolv.conf ;
RUN \ 
        apt-get install curl;\
        apt-get install -y sphinxsearch  libsphinxclient-0.0.1 sphinxbase-utils ;\
        sed -i "s/START=no/START=yes/" /etc/default/sphinxsearch; \
        echo "extension=sphinx.so" > /etc/php5/mods-available/sphinx.ini ;\
        ln -sv /etc/php5/mods-available/sphinx.ini  /etc/php5/fpm/conf.d/30-sphinx.ini;\
        service mysql start  && echo 'create user sphinx_u@localhost identified by "search_perfect";grant SELECT on knowledge.* to sphinx_u@localhost '| mysql -uroot -p'kf_kf_kf' ; \
        echo '*/5 * * * *  /usr/bin/indexer --config /etc/sphinxsearch/sphinx.conf --all --rotate >/dev/null 2>&1' |crontab

ADD docker_resources/sphinx.conf /etc/sphinxsearch/sphinx.conf


COPY docker_resources/nginx_default.conf /etc/nginx/sites-enabled/default
COPY docker_resources/start.sh /
RUN chmod +x /start.sh

ADD . /var/www/carbon_forum/
RUN chown  -R www-data /var/www/carbon_forum ; rm -rf /var/www/carbon_forum/{docker_resources,Dockerfile}
ENTRYPOINT ["/start.sh"]
WORKDIR /var/www/carbon_forum


#18 4 * * *  /usr/local/bin/indexer --config /data/sphinx/sphinx.conf --all --rotate >/dev/null 2>&1

