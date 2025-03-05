# Gestion des Collectes de Déchets 🌍

## Contexte

L'association "Littoral Propre" œuvre pour la préservation de nos plages en organisant des collectes de déchets. Face à l'augmentation de ses activités et de ses bénévoles, l'association avait besoin d'un outil de gestion efficace.

Ce back-office a été développé pour répondre à 4 besoins principaux :
- 👥 **Gestion des bénévoles** : Suivi des participants et de leurs rôles
- 📝 **Enregistrement des collectes** : Documentation des actions de nettoyage
- 🗑️ **Suivi des déchets** : Classification et quantification des déchets collectés


Cette solution permet à l'association de :
- Optimiser l'organisation des collectes
- Mesurer concrètement leur impact environnemental
- Sensibiliser le public avec des données précises
- Améliorer la coordination des bénévoles

## Description
Cette application web permet de gérer les collectes de déchets réalisées par des bénévoles. Elle offre une interface intuitive pour suivre les collectes, les bénévoles et les types de déchets ramassés.

## Fonctionnalités principales

### Gestion des collectes
- 📊 Visualisation de toutes les collectes
- ➕ Ajout d'une nouvelle collecte
- ✏️ Modification des informations d'une collecte
- 🗑️ Suppression d'une collecte
- 📈 Suivi des quantités par type de déchets

### Gestion des bénévoles
- 👥 Liste des bénévoles participants
- ➕ Ajout de nouveaux bénévoles
- 🔄 Modification des informations des bénévoles
- 🗑️ Suppression de bénévoles
- 👑 Gestion des rôles (admin/participant)

## Technologies utilisées
- PHP 8.3
- MySQL 8.0
- HTML5/CSS3
- Tailwind CSS
- JavaScript

## Accessibilité (RGAA)

Notre application respecte les normes d'accessibilité RGAA (Référentiel Général d'Amélioration de l'Accessibilité) avec les fonctionnalités suivantes :

### Navigation
- ⌨️ Navigation possible au clavier
- 🔍 Structure de navigation cohérente
- 🎯 Liens et boutons explicites avec des attributs `aria-label`
- 📱 Focus visible sur tous les éléments interactifs

### Contenu
- 📝 Textes alternatifs pour les images
- 🎨 Contrastes de couleurs respectés
- 📊 Tableaux de données avec en-têtes appropriés
- 📱 Formulaires avec labels associés et messages d'erreur explicites

### Structure
- 🏗️ Hiérarchie de titres cohérente
- 📑 Landmarks ARIA pour les zones principales
- 🔤 Langue de la page déclarée
- 📱 Structure sémantique HTML5

### Améliorations prévues
- [ ] Ajout de raccourcis clavier
- [ ] Amélioration des messages d'erreur
- [ ] Tests avec différents lecteurs d'écran
- [ ] Documentation d'accessibilité complète 

## À venir
- [ ] Système de statistiques avancées
- [ ] Export des données en CSV/PDF
- [ ] Interface mobile responsive
- [ ] Système de notifications
- [ ] Cartographie des collectes
- 🔒 Authentification requise
- 🔐 Gestion des sessions
- 🛡️ Protection contre les injections SQL
- 🔑 Hachage des mots de passe
