# SymfonyProj — Blog

Projet Symfony 7.4 avec Docker, MySQL et phpMyAdmin.

## Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/)

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/Darkft28/SymfonyProj.git
cd SymfonyProj
```

### 2. Configurer les variables d'environnement

Créer un fichier `.env.local` à la racine et y mettre la vraie `DATABASE_URL` :

```bash
DATABASE_URL="mysql://symfony:symfony@database:3306/symfony_db?serverVersion=8.0&charset=utf8mb4"
APP_SECRET=4cd698858c4156011a42ffdb8c82defd
```

### 3. Lancer les containers Docker

```bash
docker-compose up -d --build
```

### 4. Exécuter les migrations

```bash
docker exec symfony_php php bin/console doctrine:migrations:migrate --no-interaction
```

### 5. Accéder au projet

| Service | URL |
|---|---|
| Application | http://localhost:8080 |
| Login | http://localhost:8080/login |
| phpMyAdmin | http://localhost:8081 |

## Identifiants base de données

| | |
|---|---|
| Hôte | `database` (depuis le container) ou `localhost:3306` (depuis l'hôte) |
| Base | `symfony_db` |
| Utilisateur | `symfony` |
| Mot de passe | `symfony` |
| Root | `root` |

## Commandes utiles

```bash
# Vider le cache
docker exec symfony_php php bin/console cache:clear

# Créer une nouvelle migration après modification d'une entité
docker exec symfony_php php bin/console make:migration
docker exec symfony_php php bin/console doctrine:migrations:migrate

# Voir les routes
docker exec symfony_php php bin/console debug:router

# Arrêter les containers
docker-compose down
```

## Structure du projet

```
src/
├── Controller/
│   ├── HomeController.php
│   └── SecurityController.php
├── Entity/
│   ├── Article.php
│   ├── Category.php
│   ├── Comment.php
│   └── User.php
└── Repository/
    ├── ArticleRepository.php
    ├── CategoryRepository.php
    ├── CommentRepository.php
    └── UserRepository.php
```
