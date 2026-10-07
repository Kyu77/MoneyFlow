# Runbook — Deployment indisponible

## Alerte

```text
MoneyFlowDeploymentUnavailable
```

### Sévérité

```text
critical
```

### Condition

L'alerte se déclenche lorsqu'un Deployment MoneyFlow ne possède aucune réplique disponible pendant plus d'une minute.

```promql
kube_deployment_status_replicas_available{namespace="default", deployment=~"moneyflow-.*"} < 1
```

## 1. Vérifier le Deployment

Lister les Deployments :

```bash
kubectl get deployment
```

Vérifier le Deployment concerné :

```bash
kubectl get deployment moneyflow-nginx
```

## 2. Vérifier les Pods

```bash
kubectl get pods
```

Rechercher notamment :

* des Pods qui ne sont pas `Running` ;
* `CrashLoopBackOff` ;
* `ImagePullBackOff` ;
* `Pending` ;
* des problèmes liés aux probes de readiness ou de liveness.

## 3. Inspecter le Deployment

```bash
kubectl describe deployment moneyflow-nginx
```

Vérifier notamment :

* le nombre de répliques souhaitées ;
* le nombre de répliques disponibles ;
* les événements Kubernetes ;
* l'état du ReplicaSet.

## 4. Inspecter le Pod

Si un Pod existe mais semble en difficulté :

```bash
kubectl describe pod <pod-name>
```

Puis consulter ses logs :

```bash
kubectl logs <pod-name>
```

## 5. Appliquer la remédiation

La remédiation dépend de la cause identifiée.

Lors de la simulation réalisée sur MoneyFlow, le Deployment avait volontairement été réduit à zéro réplique.

Il a été restauré avec :

```bash
kubectl scale deployment moneyflow-nginx --replicas=1
```

## 6. Vérifier le retour à la normale

Vérifier le Deployment :

```bash
kubectl get deployment moneyflow-nginx
```

Puis les Pods :

```bash
kubectl get pods
```

Le Deployment doit de nouveau posséder une réplique disponible et le Pod doit être opérationnel.

L'alerte Prometheus doit ensuite revenir à son état normal.

## Workflow d'incident

```text
Alerte
  ↓
kubectl get deployment
  ↓
kubectl get pods
  ↓
kubectl describe
  ↓
kubectl logs
  ↓
Identification de la cause
  ↓
Remédiation
  ↓
Vérification
  ↓
Confirmation de la résolution de l'alerte
```
