# Observabilité

## Vue d'ensemble

MoneyFlow utilise Prometheus et Grafana pour superviser l'application déployée sur Kubernetes.

La stack d'observabilité est séparée de l'application elle-même :

```text
MoneyFlow
    │
    │ métriques
    ▼
Prometheus :9090
    │
    │ PromQL
    ▼
Grafana :3000
    │
    └── Dashboard MoneyFlow
```

## Prometheus

Prometheus collecte et stocke les métriques provenant de l'environnement Kubernetes et de ses composants.

Prometheus est accessible localement sur :

```text
http://localhost:9090
```

Il est également responsable de l'évaluation des règles d'alerte.

Une `PrometheusRule` a notamment été créée afin de détecter lorsqu'un Deployment MoneyFlow ne possède plus aucune réplique disponible.

## Grafana

Grafana permet de visualiser les métriques collectées par Prometheus.

Grafana est accessible localement sur :

```text
http://localhost:3000
```

Un dashboard MoneyFlow a été créé afin de visualiser les principales métriques de l'environnement.

Grafana utilise Prometheus comme source de données et interroge les métriques avec PromQL.

## Alerting

Une alerte Prometheus a été créée afin de détecter l'indisponibilité d'un Deployment MoneyFlow.

La condition surveillée est :

```promql
kube_deployment_status_replicas_available{namespace="default", deployment=~"moneyflow-.*"} < 1
```

L'alerte doit rester dans cet état pendant une minute avant de se déclencher :

```yaml
for: 1m
```

Cela permet d'éviter de déclencher une alerte pour un problème très temporaire.

L'alerte est nommée :

```text
MoneyFlowDeploymentUnavailable
```

avec une sévérité :

```text
critical
```

## Simulation d'incident

L'alerte a été testée volontairement en mettant le Deployment `moneyflow-nginx` à zéro réplique :

```bash
kubectl scale deployment moneyflow-nginx --replicas=0
```

L'état de l'alerte a ensuite été vérifié directement depuis Prometheus.

L'alerte est passée par les états :

```text
Pending → Firing
```

Une fois le test terminé, le Deployment a été restauré :

```bash
kubectl scale deployment moneyflow-nginx --replicas=1
```

## Diagnostic

Lorsqu'une alerte d'indisponibilité se déclenche, les premières commandes utilisées pour diagnostiquer le problème sont :

```bash
kubectl get pods
kubectl get deployment
kubectl describe deployment moneyflow-nginx
kubectl describe pods
kubectl logs <pod>
```

L'objectif est d'identifier la cause de l'indisponibilité, par exemple :

* une absence de réplique disponible ;
* un Pod qui a redémarré ou qui est en erreur ;
* un problème de scheduling ;
* un problème d'image ou de conteneur ;
* une erreur applicative ;
* un problème de configuration Kubernetes.

## Chaîne d'observabilité

Le fonctionnement peut être résumé ainsi :

```text
Métrique
   ↓
Prometheus
   ↓
Alerte
   ↓
Diagnostic avec kubectl
   ↓
Remédiation
   ↓
Vérification
```

L'objectif est donc de transformer un signal technique en action opérationnelle : détecter le problème, l'analyser, appliquer une remédiation puis vérifier le retour à un état normal.
