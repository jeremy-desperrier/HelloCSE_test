# HelloCSE_test
Projet Laravel 11 réalisé dans le cadre du test technique de l’entreprise HelloCSE.

# Test technique
---

## Objectif

À l’aide de Laravel 10/11 créer une API qui possède :

### 🔐 Entité administrateur
- Une entité “administrateur” (seuls utilisateurs authentifiés sur le projet).  
Les champs composant cette entité ne sont pas importants, l’idée étant simplement de protéger certains endpoints de l’application.

### 👤 Création de profil (endpoint protégé)
- Un endpoint protégé par authentification, qui permet de créer une entité “profil”.  
Ces profils possèderont les champs suivants :
  - nom,
  - prénom,
  - image (un véritable fichier),
  - statut (inactif, en attente, actif),
  - timestamps classique.

### 🌍 Récupération des profils actifs (endpoint public)
- Un endpoint public qui permet de récupérer l’ensemble des profils uniquement dans le statut “actif”, et qui ne retourne pas le champ “statut” (champ accessible uniquement pour les utilisateurs authentifiés).

### ✏️ Modification et suppression (endpoint protégé)
- Un endpoint protégé par authentification, qui permet de modifier un profil ou de le supprimer

---

## Indications

- Les données doivent être typées et validées (FormRequest)
- L’utilisation de seeders, factories, tests unitaires est un plus
- N’hésitez pas à commenter votre code et créer des commits sur Git au fur et à mesure de votre progression


# Démarage

## ⚙️ Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### Configurer la base de données dans le fichier .env

```Dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=HelloCSE_jeremy_DESPERRIER
DB_USERNAME= !! à changer en fonction de vos besoin
DB_PASSWORD= !! à changer en fonction de vos besoin
```

#### puis exécuter :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## 🔐 Accès administrateur

Un utilisateur administrateur est automatiquement créé via les seeders :
```
Email : admin@hellocse.test
Mot de passe : password
```
