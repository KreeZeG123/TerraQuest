FROM mysql:8.0

# Définir les variables d’environnement pour MySQL
ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=main
ENV MYSQL_USER=admin
ENV MYSQL_PASSWORD=admin

# Copier le script d'initialisation si nécessaire
COPY docker/mysql/init.sql /docker-entrypoint-initdb.d/

# Exposer le port MySQL
EXPOSE 3306

CMD ["mysqld"]
