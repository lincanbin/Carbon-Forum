FROM tutum/lamp
MAINTAINER istrwei <admin@strwei.com>

RUN rm -fr /app && git clone https://github.com/lincanbin/Carbon-Forum.git /app

EXPOSE 80 3306
CMD ["/run.sh"]
