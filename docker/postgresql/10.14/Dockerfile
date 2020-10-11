FROM postgres:10.14

ADD setup.sql /docker-entrypoint-initdb.d/setup.sql
RUN localedef -i ja_JP -c -f UTF-8 -A /usr/share/locale/locale.alias ja_JP.UTF-8
RUN echo "host all all 0.0.0.0/0 md5" >> /var/lib/postgresql/data/pg_hba.conf

ENV LANG ja_JP.utf8