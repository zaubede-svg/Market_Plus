# Marché AUBEDE

Boutique PHP + MySQL avec espace administrateur.

## Stack
- PHP 8.1+
- MySQL/MariaDB
- PDO
- HTML/CSS/JavaScript
- Sessions PHP
- Protection CSRF pour l'administration

## Installation
1. Importer `database/schema.sql` dans MySQL/phpMyAdmin.
2. Copier `config/database.example.php` vers `config/database.php`.
3. Renseigner les identifiants MySQL dans `config/database.php`.
4. Ouvrir `/admin/setup.php` une seule fois.
5. Compte initial:
   - Identifiant: `admin`
   - Mot de passe: `Marina2026`
   - Nom: `ZOUNTCHEGBE AUBEDE`
6. Supprimer `admin/setup.php` après création du compte.
7. Se connecter sur `/admin/login.php`.

## Déploiement
GitHub stocke le code. Il faut un hébergement PHP + MySQL pour exécuter le site.
Ne jamais publier `config/database.php` ni les mots de passe réels.
