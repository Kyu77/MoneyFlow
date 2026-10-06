# Runbook — Nginx ne démarre pas : échec du bind mount

## Symptôme

MoneyFlow devient inaccessible :

```bash
curl -I http://localhost:8080
```

retourne une erreur de connexion.

Le conteneur Nginx apparaît comme arrêté :

```bash
docker compose ps -a
```

avec par exemple :

```text
moneyflow-nginx   Exited (127)
```

## Diagnostic

Vérifier les logs :

```bash
docker compose logs --tail=50 nginx
```

Les logs peuvent uniquement montrer l'arrêt du processus Nginx. Dans ce cas, obtenir l'erreur du runtime Docker avec :

```bash
docker inspect moneyflow-nginx \
  --format='Status={{.State.Status}} ExitCode={{.State.ExitCode}} Error={{.State.Error}}'
```

Dans l'incident rencontré :

```text
ExitCode=127
```

avec :

```text
OCI runtime create failed
error during container init
error mounting ... default.conf
no such file or directory
```

## Investigation

Vérifier que le fichier de configuration existe :

```bash
ls -la docker/nginx/
```

Puis :

```bash
file docker/nginx/default.conf
```

Le fichier était bien présent et était un fichier texte valide.

Vérifier également le chemin réellement utilisé par Compose :

```bash
docker compose config
```

Le fichier source était correctement identifié :

```text
/home/devops/projects/MoneyFlow/docker/nginx/default.conf
```

Un test indépendant a ensuite permis de vérifier que Docker pouvait monter le fichier :

```bash
docker run --rm \
  -v "$(pwd)/docker/nginx/default.conf:/tmp/test.conf:ro" \
  alpine:latest \
  cat /tmp/test.conf
```

Le montage fonctionnait.

## Cause

L'ancien conteneur Nginx utilisait un chemin intermédiaire de bind mount Docker Desktop/WSL devenu invalide.

Le fichier source et la configuration du projet étaient corrects.

Le problème concernait donc le montage conservé par l'ancien conteneur.

## Remédiation

Supprimer uniquement le conteneur Nginx :

```bash
docker compose rm -sf nginx
```

Puis le recréer :

```bash
docker compose up -d nginx
```

Vérifier :

```bash
docker compose ps
```

Puis :

```bash
curl -I http://localhost:8080
```

Résultat attendu :

```text
HTTP/1.1 200 OK
```

## Raisonnement RUN

```text
HTTP inaccessible
    ↓
Nginx Exited (127)
    ↓
PHP toujours Up
    ↓
docker inspect
    ↓
OCI runtime / bind mount failure
    ↓
vérification du fichier source
    ↓
test indépendant du bind mount
    ↓
recréation du conteneur Nginx
    ↓
HTTP 200
```

## Point à retenir

Une erreur affichée au niveau d'un conteneur ne signifie pas nécessairement que l'application ou sa configuration est incorrecte.

Il faut déterminer à quelle couche se situe le problème :

* application ;
* serveur web ;
* conteneur ;
* runtime Docker ;
* système de fichiers / bind mount.
