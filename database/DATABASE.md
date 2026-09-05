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


---

# Structure détaillée

## users

La table `users` est gérée par Laravel et contient les informations d'authentification.

Champs principaux :

- id
- name
- email
- password
- email_verified_at
- created_at
- updated_at

---

## accounts

Représente les comptes financiers de l'utilisateur.

### Champs

- id
- user_id
- name
- type
- balance
- is_shared
- created_at
- updated_at

### Types de comptes

- current : compte courant
- savings : compte d'épargne
- joint : compte joint
- other : autre compte

### Règles

- Un utilisateur peut posséder plusieurs comptes.
- Chaque compte possède son propre solde.
- Les soldes des comptes restent indépendants.
- Le total des comptes peut être affiché à titre informatif.
- Le total des comptes ne doit pas être présenté comme le montant disponible pour les dépenses.
- `user_id` permet de rattacher chaque compte à son propriétaire.

---

## transactions

Représente les opérations financières réellement effectuées.

### Champs

- id
- account_id
- category_id
- type
- amount
- description
- transaction_date
- transfer_id
- created_at
- updated_at

### Types

- income : revenu
- expense : dépense
- transfer : transfert

### Règles

- Une transaction appartient à un compte.
- Une dépense diminue le solde du compte.
- Un revenu augmente le solde du compte.
- Un transfert déplace de l'argent entre deux comptes.
- Un transfert n'est pas considéré comme une dépense.
- Le montant est toujours stocké comme une valeur positive.
- Le type de transaction détermine son impact sur le solde.
- Les deux mouvements d'un transfert doivent être liés grâce à un identifiant commun.
- Une transaction réelle modifie le solde réel du compte.

### Exemple

Compte courant : 1 200 €

Dépense restaurant :

- type : expense
- amount : 35.00

Nouveau solde : 1 165 €

### Exemple de transfert

Compte courant : 1 200 €
Livret A : 3 300 €

Transfert : 500 €

Compte courant : 700 €
Livret A : 3 800 €

Le patrimoine total reste identique.

---

## categories

Représente les catégories utilisées pour classer les transactions.

### Champs

- id
- user_id
- name
- type
- icon
- created_at
- updated_at

### Types

- expense : catégorie de dépense
- income : catégorie de revenu

Les transferts ne possèdent pas de catégorie.

### Règles

- Une catégorie appartient à un utilisateur.
- Un utilisateur peut créer ses propres catégories.
- Les catégories sont privées à leur utilisateur.
- Une transaction peut être associée à une catégorie.
- Une catégorie peut être utilisée par plusieurs transactions.
- Une catégorie peut être utilisée pour les dépenses ou les revenus.
- Les transferts ne sont pas catégorisés.

### Exemples

Dépenses :

- Alimentation
- Transport
- Logement
- Loisirs
- Abonnements
- Santé

Revenus :

- Salaire
- Prime
- Freelance
- Autre revenu

---

## recurring_transactions

Représente une opération financière prévue de manière récurrente.

Exemples :

- Salaire
- Loyer
- Netflix
- Assurance
- Téléphone
- Abonnement

### Champs

- id
- user_id
- account_id
- category_id
- type
- name
- amount
- frequency
- next_occurrence
- is_active
- created_at
- updated_at

### Types

- expense : dépense récurrente
- income : revenu récurrent

Les transferts récurrents ne sont pas gérés dans cette première version.

### Fréquences

- daily : quotidienne
- weekly : hebdomadaire
- monthly : mensuelle
- yearly : annuelle

### Règles

- Une opération récurrente appartient à un utilisateur.
- Une opération récurrente est associée à un compte.
- Une opération récurrente peut être associée à une catégorie.
- Une opération récurrente ne modifie pas directement le solde réel.
- Lorsqu'une échéance arrive, l'utilisateur doit confirmer l'opération.
- Si l'utilisateur confirme, une transaction réelle est créée.
- Si l'utilisateur refuse, aucune transaction réelle n'est créée.
- Une opération récurrente peut être désactivée sans être supprimée.
- Une modification ponctuelle d'une occurrence ne doit pas modifier la règle récurrente.

### Exemple

Netflix :

- type : expense
- name : Netflix
- amount : 15.99
- frequency : monthly
- next_occurrence : 2026-09-10

Avant confirmation :

Le solde réel ne change pas.

Après confirmation :

Une transaction réelle de 15,99 € est créée et le solde du compte est diminué.


---

## planned_transactions

Représente une opération financière ponctuelle prévue dans le futur.

Exemples :

- Restaurant prévu
- Achat prévu
- Réparation de voiture
- Cadeau
- Voyage
- Facture exceptionnelle

### Champs

- id
- user_id
- account_id
- category_id
- type
- name
- estimated_amount
- planned_date
- status
- notes
- created_at
- updated_at

### Types

- expense : dépense prévue
- income : revenu prévu

### Statuts

- planned : opération prévue
- completed : opération réalisée
- cancelled : opération annulée

### Règles

- Une opération prévue n'est pas une transaction réelle.
- Une opération prévue ne modifie jamais le solde réel.
- Le montant prévu peut être une estimation.
- Lorsqu'une dépense prévue est réellement effectuée, une transaction réelle doit être créée.
- Le montant réel peut être différent du montant estimé.
- Une opération prévue réalisée ne doit pas créer une deuxième dépense dans les prévisions.
- Une opération annulée ne doit pas modifier le solde réel.

### Exemple

Dépense prévue :

Restaurant samedi : 40 €

Solde réel : 1 200 €

Prévision :

1 200 € - 40 € = 1 160 €

Le solde réel reste à 1 200 €.

Si la dépense réelle est finalement de 32 € :

Transaction réelle :

- type : expense
- amount : 32.00

Nouveau solde réel :

1 168 €

La dépense prévue passe au statut `completed`.

---

## savings_goals

Représente un objectif d'épargne.

Exemples :

- Vacances
- Voiture
- Épargne de sécurité
- Ordinateur
- Projet personnel

### Champs

- id
- user_id
- name
- target_amount
- target_date
- is_completed
- created_at
- updated_at

### Règles

- Un utilisateur peut avoir plusieurs objectifs d'épargne.
- Un objectif appartient à un utilisateur.
- Le montant cible doit être supérieur à zéro.
- La date cible est facultative.
- Un objectif est considéré comme terminé lorsque le montant épargné atteint ou dépasse le montant cible.
- La progression est calculée à partir des versements associés à l'objectif.
- L'objectif d'épargne ne modifie pas directement le solde réel d'un compte.
- Une véritable mise de côté doit correspondre à une opération financière réelle.

---

## savings_contributions

Représente un versement effectué vers un objectif d'épargne.

### Champs

- id
- savings_goal_id
- amount
- contribution_date
- notes
- created_at
- updated_at

### Règles

- Un versement appartient à un objectif d'épargne.
- Un objectif peut avoir plusieurs versements.
- Le montant doit être supérieur à zéro.
- Le total épargné correspond à la somme des versements.
- La progression est calculée à partir du total des versements.

### Exemple

Objectif :

Vacances

Montant cible :

2 000 €

Versements :

- 200 €
- 150 €
- 500 €

Total épargné :

850 €

Progression :

42,5 %

## 9. Relations entre les tables

### users → accounts
Un utilisateur peut avoir plusieurs comptes.
Un compte appartient à un seul utilisateur.

Relation :
users 1 → N accounts

### users → categories
Un utilisateur peut avoir plusieurs catégories.
Une catégorie appartient à un seul utilisateur.

Relation :
users 1 → N categories

### accounts → transactions
Un compte peut avoir plusieurs transactions.
Une transaction appartient à un seul compte.

Relation :
accounts 1 → N transactions

### categories → transactions
Une catégorie peut être utilisée par plusieurs transactions.
Une transaction peut avoir une catégorie.

Relation :
categories 1 → N transactions

### users → recurring_transactions
Un utilisateur peut avoir plusieurs opérations récurrentes.
Une opération récurrente appartient à un seul utilisateur.

Relation :
users 1 → N recurring_transactions

### accounts → recurring_transactions
Un compte peut avoir plusieurs opérations récurrentes.
Une opération récurrente appartient à un seul compte.

Relation :
accounts 1 → N recurring_transactions

### categories → recurring_transactions
Une catégorie peut être utilisée par plusieurs opérations récurrentes.
Une opération récurrente peut avoir une catégorie.

Relation :
categories 1 → N recurring_transactions

### users → planned_transactions
Un utilisateur peut avoir plusieurs opérations planifiées.
Une opération planifiée appartient à un seul utilisateur.

Relation :
users 1 → N planned_transactions

### accounts → planned_transactions
Un compte peut avoir plusieurs opérations planifiées.
Une opération planifiée appartient à un seul compte.

Relation :
accounts 1 → N planned_transactions

### categories → planned_transactions
Une catégorie peut être utilisée par plusieurs opérations planifiées.
Une opération planifiée peut avoir une catégorie.

Relation :
categories 1 → N planned_transactions

### users → savings_goals
Un utilisateur peut avoir plusieurs objectifs d'épargne.
Un objectif d'épargne appartient à un seul utilisateur.

Relation :
users 1 → N savings_goals

### savings_goals → savings_contributions
Un objectif d'épargne peut avoir plusieurs contributions.
Une contribution appartient à un seul objectif.

Relation :
savings_goals 1 → N savings_contributions