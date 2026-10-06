# Docker — MoneyFlow

## 1. Objectif

MoneyFlow est une application Laravel conteneurisée avec Docker.

L'objectif est de disposer d'un environnement reproductible, dans lequel :

* Laravel s'exécute avec PHP-FPM ;
* les assets frontend sont compilés avec Node.js, Vite et Tailwind ;
* Nginx sert les fichiers statiques et transmet les requêtes PHP à PHP-FPM ;
* les données persistantes sont stockées dans des volumes Docker.

L'application est accessible sur :

`http://localhost:8080`

---

## 2. Architecture

```text
                    Navigateur
                        │
                        │ HTTP :8080
                        ▼
                ┌───────────────┐
                │    Nginx      │
                │   Alpine      │
                └───────┬───────┘
                        │
              ┌─────────┴─────────┐
              │                   │
       fichiers statiques       FastCGI
       CSS / JS / images           │
              │                    ▼
              │             ┌───────────────┐
              │             │  PHP-FPM      │
              │             │   Laravel     │
              │             └───────────────┘
              │                    │
              └────────────────────┘
                       │
                 volume public

          ┌───────────────────────────┐
          │   moneyflow-database      │
          │       SQLite              │
          └───────────────────────────┘
```

---

## 3. Dockerfile multi-stage

Le Dockerfile utilise deux stages.

### Stage `assets`

Le premier stage utilise Node.js :

```dockerfile
FROM node:22-alpine AS assets
```

Il installe les dépendances frontend avec :

```bash
npm ci
```

Puis Vite compile les assets :

```bash
npm run build
```

Cette étape produit notamment :

```text
public/build/manifest.json
public/build/assets/*.css
public/build/assets/*.js
```

Le stage contient également :

* `vite.config.js`
* `tailwind.config.js`
* `postcss.config.js`
* `resources/`

Les fichiers de configuration Tailwind et PostCSS sont nécessaires pour que Tailwind soit réellement compilé.

### Stage PHP

Le second stage utilise :

```dockerfile
FROM php:8.2-fpm
```

Il installe Composer et les extensions PHP nécessaires, puis installe les dépendances Laravel avec :

```bash
composer install
```

Le code Laravel est ensuite copié dans l'image.

Les assets générés dans le stage Node sont récupérés avec :

```dockerfile
COPY --from=assets /app/public/build ./public/build
```

Le conteneur expose PHP-FPM sur :

```text
9000
```

---

## 4. Docker Compose

L'application utilise deux conteneurs principaux :

```text
moneyflow-php
moneyflow-nginx
```

Le conteneur PHP exécute Laravel.

Le conteneur Nginx reçoit les requêtes HTTP sur le port :

```text
8080
```

Nginx transmet les requêtes PHP à :

```text
moneyflow-php:9000
```

---

## 5. Volumes Docker

Trois volumes sont utilisés :

```text
moneyflow-storage
moneyflow-database
moneyflow-public
```

### `moneyflow-storage`

Conserve les fichiers générés par Laravel dans :

```text
/app/storage
```

### `moneyflow-database`

Conserve la base SQLite dans :

```text
/app/database
```

Ce volume contient donc des données persistantes.

Il ne faut pas le supprimer lors d'une simple opération de maintenance Docker.

### `moneyflow-public`

Permet de partager le dossier :

```text
/app/public
```

entre PHP-FPM et Nginx.

C'est notamment ce qui permet à Nginx de servir les assets générés par Vite.

---

# 6. Incident : Tailwind non compilé

## Symptôme

L'application Laravel fonctionnait mais l'interface apparaissait sans son style Tailwind.

Le navigateur recevait bien un fichier CSS, mais celui-ci contenait encore des directives telles que :

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

et des règles `@apply` non compilées.

Le CSS faisait environ 1,6 Ko alors qu'un build Tailwind correct produisait un fichier d'environ 62 Ko.

---

## Diagnostic

Le build frontend était exécuté dans le stage Node du Dockerfile.

Cependant, le stage ne recevait pas les fichiers :

```text
tailwind.config.js
postcss.config.js
```

Vite/Tailwind ne disposait donc pas de la configuration nécessaire pour compiler correctement le CSS.

---

## Correction

Les fichiers de configuration ont été copiés dans le stage `assets` :

```dockerfile
COPY tailwind.config.js ./
COPY postcss.config.js ./
```

Le build a ensuite été relancé sans cache :

```bash
docker compose build --no-cache app
```

Le résultat indiquait notamment :

```text
public/build/assets/app-B7e8GTpf.css   61.92 kB
public/build/assets/app-WC-ZjLzv.js   106.90 kB
```

Cela confirmait que Tailwind était correctement compilé.

---

# 7. Deuxième problème : anciens assets

Après la reconstruction de l'image, l'application utilisait encore d'anciens fichiers CSS.

Le problème ne venait plus du build.

Les anciens fichiers étaient toujours présents dans le volume :

```text
moneyflow-public
```

Le volume public a donc été supprimé uniquement :

```bash
docker compose down
docker volume rm moneyflow_moneyflow-public
```

Puis les conteneurs ont été recréés :

```bash
docker compose up -d --force-recreate
```

Le volume contenant la base SQLite n'a pas été supprimé.

---

# 8. Vérification

Les nouveaux assets étaient présents dans le conteneur PHP et dans Nginx.

Le navigateur demandait finalement :

```text
app-B7e8GTpf.css
```

et ce fichier faisait environ 61 Ko.

Le frontend était alors correctement stylé.

---

# 9. Commandes utiles

### Reconstruire l'image

```bash
docker compose build app
```

### Rebuild complet sans cache

```bash
docker compose build --no-cache app
```

### Voir les conteneurs

```bash
docker compose ps
```

### Voir les logs

```bash
docker compose logs
```

### Voir les logs PHP

```bash
docker compose logs app
```

### Voir les logs Nginx

```bash
docker compose logs nginx
```

### Vérifier les assets

```bash
docker compose exec nginx ls -lh /app/public/build/assets/
```

### Vérifier quel CSS est demandé par Laravel

```bash
curl -s http://localhost:8080 | grep -E 'app-.*\.css'
```

---

# 10. Leçon DevOps

Cet incident montre qu'un problème visible dans le navigateur ne se situe pas nécessairement dans le code applicatif.

Le raisonnement suivi a été :

```text
Symptôme
   ↓
Observation
   ↓
Vérification du fichier CSS
   ↓
Hypothèse : problème de build
   ↓
Vérification du Dockerfile
   ↓
Correction de la configuration Tailwind
   ↓
Rebuild
   ↓
Nouveau problème : ancien volume
   ↓
Recréation ciblée du volume public
   ↓
Vérification
   ↓
Résolution
```

L'objectif est de reproduire ce type de raisonnement pour les futurs incidents Kubernetes, CI/CD et infrastructure.
