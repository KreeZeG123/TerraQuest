-- Créer la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS main;

-- Créer un utilisateur admin avec un mot de passe sécurisé
CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED BY 'admin';

-- Donner tous les privilèges à admin sur la base main
GRANT ALL PRIVILEGES ON main.* TO 'admin'@'%';

-- Appliquer les changements
FLUSH PRIVILEGES;
