# Runbook — Application inaccessible : Nginx arrêté

## Symptôme

L'application MoneyFlow est inaccessible via :

```text
http://localhost:8080
```

Le client HTTP retourne une erreur de connexion.

## Diagnostic

### 1. Vérifier l'état des services

```bash
docker compose ps
```

Si `moneyflow-php` est `Up` mais que `moneyflow-nginx` n'apparaît pas parmi les services actifs, vérifier les conteneurs arrêtés :

```bash
docker compose ps -a
```

Exemple :

```text
moneyflow-nginx   Exited (0)
moneyflow-php     Up
```

### 2. Vérifier les logs Nginx

```bash
docker compose logs --tail=20 nginx
```

Les logs permettent de déterminer si Nginx a rencontré une erreur avant son arrêt.

### 3. Vérifier le code de sortie

```bash
docker inspect moneyflow-nginx \
  --format '{{.State.Status}} | ExitCode={{.State.ExitCode}} | StartedAt={{.State.StartedAt}} | FinishedAt={{.State.FinishedAt}}'
```

Un `ExitCode=0` indique que le processus s'est terminé normalement.

Dans le scénario étudié, le conteneur avait été arrêté volontairement avec :

```bash
docker compose stop nginx
```

## Remédiation

Redémarrer uniquement le service concerné :

```bash
docker compose start nginx
```

## Vérification

Vérifier que les deux services sont actifs :

```bash
docker compose ps
```

Puis tester l'accès HTTP :

```bash
curl -I http://localhost:8080
```

Résultat attendu :

```text
HTTP/1.1 200 OK
```

## Raisonnement RUN

```text
Symptôme
→ Vérification de l'état des services
→ Identification du service arrêté
→ Analyse des logs
→ Vérification du code de sortie
→ Remise en service
→ Vérification fonctionnelle
```

## Points d'attention

`Exited (0)` ne signifie pas que l'application est fonctionnelle. Il signifie que le processus s'est terminé avec un code de sortie normal.

Il faut toujours effectuer une vérification fonctionnelle après la remédiation.
