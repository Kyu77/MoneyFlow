# Runbook — Application indisponible : Laravel en maintenance

## Symptôme

L'application MoneyFlow est inaccessible et retourne une réponse HTTP `503 Service Unavailable`.

Les conteneurs Docker peuvent cependant apparaître comme étant `Up`.

## Diagnostic

Vérifier l'état des conteneurs :

```bash
docker compose ps
```

Si `moneyflow-nginx` et `moneyflow-php` sont tous les deux `Up`, le problème ne vient pas nécessairement de Docker.

Tester la réponse HTTP :

```bash
curl -I http://localhost:8080
```

Une réponse :

```text
HTTP/1.1 503 Service Unavailable
```

indique que la requête atteint bien Nginx et l'application.

Vérifier ensuite l'état de Laravel :

```bash
docker compose exec app php artisan about
```

Rechercher notamment :

```text
Maintenance ........ OFF/ON
```

## Cause rencontrée

Laravel avait été placé volontairement en mode maintenance avec :

```bash
docker compose exec app php artisan down
```

Les conteneurs Docker continuaient donc de fonctionner normalement, mais Laravel refusait les requêtes HTTP avec un statut `503`.

## Remédiation

Désactiver le mode maintenance :

```bash
docker compose exec app php artisan up
```

Puis vérifier :

```bash
curl -I http://localhost:8080
```

La réponse attendue est :

```text
HTTP/1.1 200 OK
```

## Raisonnement RUN

```text
HTTP 503
    ↓
Nginx accessible
    ↓
Conteneurs Up
    ↓
Vérification de l'application
    ↓
Laravel en maintenance
    ↓
php artisan up
    ↓
HTTP 200
```

## Point à retenir

Un conteneur `Up` ne signifie pas nécessairement que l'application est disponible.

Il faut distinguer :

* état du conteneur ;
* état du serveur web ;
* état du runtime PHP ;
* état de l'application Laravel.
