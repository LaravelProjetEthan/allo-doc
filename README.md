# Parcours - Laravel - Ethan Eldib

## Projet Allo'Doc 📚 (promo Phénix - O'Clock)

L'objectif de l'application est de permettre à des patients de prendre rendez-vous auprès de praticiens.

## Guide d'installation du projet 🎯

- git clone https://github.com/ethan-eldib/allo-doc.git
- composer install (pour installer les dépendances)
- copier/coller le fichier .env.example à la racine du projet / le renommer en .env (à inclure dans le fichier gitignore)
- Le fichier .env est à configurer selon votre environnement de travail
```
    Exemple
    
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=nom_de_ma_base
    DB_USERNAME=root
    DB_PASSWORD=root
```
- php artisan migrate:fresh --seed (lance les migrations et les seeders pour peupler la base de données)
- php artisan serve (pour démarrer le serveur)

## Pour tester l'application 

- Pour vous connecter en tant qu'admin :
```
    id: john.doe@gmail.com
    mdp: badpassword
```
- Pour vous connecter en tant que praticien :
```
    id: pierre.louis@gmail.com
    mdp: badpassword
```
- Pour vous connecter en tant que patient :
```
    id: ethan@test.com
    mdp: badpassword
```
## Configuration email

Il faut modifier le fichier .env 
```
    Exemple avec outlook
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp-mail.outlook.com
    MAIL_PORT=587
    MAIL_USERNAME=mon-adresse-mail@outlook.fr
    MAIL_PASSWORD=monMotDePasseOutlook
    MAIL_ENCRYPTION=tls
    
    Exemple avec GMAIL (ne fonctionne pas si la double authentification google est activée)
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=465
    MAIL_USERNAME=mon-adresse-mail@gmail.com
    MAIL_PASSWORD=monMotDePasseGmail
    MAIL_ENCRYPTION=ssl
```