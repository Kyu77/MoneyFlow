# DATABASE — MoneyFlow

## 1. Utilisateurs

### users

Représente les utilisateurs de l'application.

Un utilisateur peut posséder plusieurs comptes financiers.

---

## 2. Comptes

### accounts

Représente les comptes financiers d'un utilisateur.

Exemples :

- Compte courant
- Livret A
- Compte joint
- Épargne
- Autre

Un utilisateur peut avoir plusieurs comptes.

Chaque compte possède son propre solde.

Le solde d'un compte ne doit pas être confondu avec le montant disponible pour les dépenses occasionnelles.

---

## 3. Transactions

### transactions

Représente les opérations financières réellement effectuées.

Types de transactions :

- income : revenu
- expense : dépense
- transfer : transfert

Une transaction appartient à un compte.

### Règles

Une dépense diminue le solde du compte.

Un revenu augmente le solde du compte.

Un transfert déplace de l'argent d'un compte vers un autre.

Un transfert ne doit pas être considéré comme une dépense.

Exemple :

Compte courant : 1 200 €
Livret A : 3 300 €

Transfert de 500 € :

Compte courant : 700 €
Livret A : 3 800 €

Le patrimoine total reste de 4 500 €.

---

## 4. Catégories

### categories

Permet de classer les dépenses et revenus.

Exemples de catégories :

- Alimentation
- Transport
- Logement
- Loisirs
- Abonnements
- Santé
- Salaire
- Autre

Les utilisateurs peuvent créer leurs propres catégories.

---

## 5. Dépenses récurrentes

### recurring_transactions

Représente les opérations prévues de manière répétitive.

Exemples :

- Loyer
- Netflix
- Assurance
- Salaire
- Abonnement téléphone

Une opération récurrente possède une fréquence et une prochaine date d'échéance.

Une dépense récurrente prévue ne devient une transaction réelle qu'après confirmation de l'utilisateur.

---

## 6. Dépenses prévues

### planned_transactions

Représente une dépense ou un revenu prévu ponctuellement dans le futur.

Exemple :

Restaurant prévu samedi : environ 40 €.

Cette opération ne doit pas modifier le solde réel du compte.

Lorsque la dépense réelle est effectuée, elle devient une transaction réelle.

---

## 7. Objectifs d'épargne

### savings_goals

Représente un objectif d'épargne.

Exemples :

- Vacances
- Voiture
- Épargne de sécurité
- Projet personnel

Un objectif possède :

- un nom
- un montant cible
- une progression
- éventuellement une date cible

L'épargne mise de côté ne doit pas être considérée comme de l'argent disponible pour les dépenses courantes.

---

## 8. Versements d'épargne

### savings_contributions

Représente les versements effectués vers un objectif d'épargne.

Un objectif peut recevoir plusieurs versements.

Exemple :

Objectif vacances : 2 000 €

Versements :

- 200 €
- 150 €
- 500 €

Total épargné : 850 €

Progression : 42,5 %

---

# Règles financières fondamentales

1. Le solde réel représente uniquement l'argent réellement présent sur le compte.
2. Une prévision ne modifie jamais le solde réel.
3. Un transfert n'est pas une dépense.
4. L'épargne n'est pas de l'argent disponible pour les dépenses courantes.
5. Une dépense récurrente prévue n'est enregistrée comme réelle qu'après confirmation.
6. Une dépense planifiée ne modifie pas le solde réel.
7. Les comptes doivent rester indépendants.
8. Le total de tous les comptes peut être affiché comme information secondaire, mais ne doit pas remplacer les soldes individuels.