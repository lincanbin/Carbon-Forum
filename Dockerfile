FROM tutum/lamp
MAINTAINER istrwei <admin@strwei.com>
# RUN apt-get install php5-gd -y
RUN rm -fr /app && git clone https://github.com/lincanbin/Carbon-Forum /app
# RUN git clone https://github.com/kalcaddle/KODExplorer.git /app/setting
EXPOSE 80 3306
CMD ["/run.sh"]
