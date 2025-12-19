# HabitFlow

Application de suivi d'habitudes construite avec Laravel 12, Inertia.js, React et Tailwind CSS.

Compte d'exemple avec les habitudes rentrées :
login : motdepasse@example.com
mot de passe : motdepasse

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine :

- **PHP** >= 8.2
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** >= 18.x et **npm** (ou **yarn**)
- **SQLite** (inclus par défaut avec PHP)

### Vérification des prérequis

```bash
php --version    # Doit afficher PHP 8.2 ou supérieur
composer --version
node --version   # Doit afficher v18.x ou supérieur
npm --version
```

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd app-store
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

Créez un fichier `.env` à partir de l'exemple (si disponible) ou configurez-le manuellement :

```bash
# Si un fichier .env.example existe
cp .env.example .env

# Sinon, créez un fichier .env avec les variables suivantes :
```

Variables d'environnement minimales requises dans `.env` :

```env
APP_NAME=HabitFlow
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

VITE_APP_NAME="${APP_NAME}"
```

### 4. Générer la clé d'application

```bash
php artisan key:generate
```

### 5. Créer la base de données SQLite

```bash
# Créer le fichier de base de données SQLite
touch database/database.sqlite
```

### 6. Exécuter les migrations

```bash
php artisan migrate
```

### 7. Installer les dépendances Node.js

```bash
npm install
```

### 8. Générer les routes Wayfinder

```bash
php artisan wayfinder:generate
```

### 9. Compiler les assets

```bash
npm run build
```

## 🎯 Démarrage rapide (méthode automatisée)

Si vous préférez une installation en une seule commande, vous pouvez utiliser le script Composer :

```bash
composer run setup
```

Ce script exécute automatiquement :
- Installation des dépendances Composer
- Copie du fichier `.env.example` vers `.env` (si nécessaire)
- Génération de la clé d'application
- Exécution des migrations
- Installation des dépendances npm
- Compilation des assets

**Note** : Vous devrez toujours créer manuellement le fichier `database/database.sqlite` et générer les routes Wayfinder si nécessaire.

## 🏃 Lancer l'application

### Mode développement

Pour lancer l'application en mode développement avec rechargement automatique :

```bash
composer run dev
```

Cette commande lance simultanément :
- Le serveur PHP (`php artisan serve`)
- La file d'attente Laravel (`php artisan queue:listen`)
- Le serveur de développement Vite (`npm run dev`)

L'application sera accessible à l'adresse : **http://localhost:8000**

### Mode production

Pour lancer uniquement le serveur PHP (après avoir compilé les assets) :

```bash
php artisan serve
```

## 📦 Commandes utiles

### Générer les routes Wayfinder

Après avoir modifié des routes, régénérez les types TypeScript :

```bash
php artisan wayfinder:generate
```

### Exécuter les migrations

```bash
php artisan migrate
```

### Créer un nouvel utilisateur (Tinker)

```bash
php artisan tinker
```

Puis dans Tinker :

```php
User::create([
    'name' => 'Votre Nom',
    'email' => 'votre@email.com',
    'password' => Hash::make('votre-mot-de-passe'),
]);
```

### Exécuter les tests

```bash
php artisan test
```

### Formater le code PHP

```bash
vendor/bin/pint
```

### Formater le code JavaScript/TypeScript

```bash
npm run format
```

## 🗄️ Base de données

Le projet utilise **SQLite** par défaut, ce qui signifie qu'aucune configuration de base de données externe n'est nécessaire. Le fichier de base de données se trouve à `database/database.sqlite`.

### Utiliser une autre base de données

Si vous souhaitez utiliser MySQL ou PostgreSQL, modifiez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=habitflow
DB_USERNAME=root
DB_PASSWORD=
```

Puis exécutez les migrations :

```bash
php artisan migrate
```

## 🛠️ Technologies utilisées

- **Backend** : Laravel 12
- **Frontend** : React 19, Inertia.js 2
- **Styling** : Tailwind CSS 4
- **Build Tool** : Vite
- **Authentification** : Laravel Fortify
- **Routing Type-Safe** : Laravel Wayfinder
- **Base de données** : SQLite (par défaut)

## 📁 Structure du projet

```
app-store/
├── app/                    # Code PHP de l'application
│   ├── Http/Controllers/   # Contrôleurs
│   ├── Models/             # Modèles Eloquent
│   └── Actions/            # Actions Fortify
├── database/               # Migrations, seeders, factories
├── resources/
│   ├── js/                 # Code JavaScript/TypeScript
│   │   ├── pages/          # Pages Inertia
│   │   ├── components/     # Composants React
│   │   ├── features/       # Fonctionnalités métier
│   │   └── routes/         # Routes Wayfinder générées
│   └── views/              # Templates Blade
├── routes/                 # Routes Laravel
├── tests/                  # Tests PHPUnit
└── public/                 # Point d'entrée public
```

## 🐛 Dépannage

### Erreur "Unable to locate file in Vite manifest"

Compilez les assets :

```bash
npm run build
```

Ou en mode développement :

```bash
npm run dev
```

### Erreur de permissions sur les dossiers

Sur Linux/Mac, assurez-vous que les dossiers `storage` et `bootstrap/cache` sont accessibles en écriture :

```bash
chmod -R 775 storage bootstrap/cache
```

### Erreur "Class not found" après installation

Régénérez l'autoloader Composer :

```bash
composer dump-autoload
```

### Les routes Wayfinder ne sont pas à jour

Générez-les à nouveau :

```bash
php artisan wayfinder:generate
```

## 📝 Notes supplémentaires

- Le projet utilise **Laravel Sail** pour Docker (optionnel)
- Les logs de l'application se trouvent dans `storage/logs/laravel.log`
- Les logs du navigateur se trouvent dans `storage/logs/browser.log`
- Le mode debug est activé par défaut en environnement local (`APP_DEBUG=true`)

## 🤝 Contribution

1. Créez une branche pour votre fonctionnalité
2. Committez vos changements
3. Poussez vers la branche
4. Ouvrez une Pull Request

## 📄 Licence

MIT

