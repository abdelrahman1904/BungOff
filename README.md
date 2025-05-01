# BungOff

## Description

**BungOff** est une plateforme web de gestion d'activités et de réservations de bungalows. L'application permet aux utilisateurs de réserver des bungalows dans plusieurs villes en Tunisie (Rafraf, Tozeur, Tabarka) et de participer à des activités locales en fonction de l'emplacement de leur bungalow. Les administrateurs peuvent gérer les activités, les réservations, et les utilisateurs via un backoffice.

## Fonctionnalités

### 1. **Frontoffice**
- **Affichage des bungalows** : L'utilisateur peut voir la liste des bungalows disponibles dans une ville spécifique.
- **Réservation des bungalows** : L'utilisateur peut réserver un bungalow selon sa disponibilité.
- **Inscription aux activités locales** : Après avoir réservé un bungalow, l'utilisateur peut choisir des activités à faire dans la région.
- **Détails des activités** : Chaque activité affichera une description, le prix, la date, et le nombre de participants.
- **Affichage des images des bungalows et activités** : Des images accompagnent chaque bungalow et chaque activité pour une meilleure visualisation.

### 2. **Backoffice**
- **Gestion des bungalows** : L'administrateur peut ajouter, modifier ou supprimer des bungalows.
- **Gestion des activités** : L'administrateur peut ajouter de nouvelles activités, les modifier ou les supprimer.
- **Consultation des réservations** : L'administrateur peut voir toutes les réservations effectuées.
- **Gestion des utilisateurs** : L'administrateur peut gérer les profils utilisateurs et leur attribuer des réservations ou des activités.

---

## 🔧 Gestion des Activités (Branche `gestion_activites`)

Cette branche contient toute la logique et les interfaces liées à la **gestion des activités** dans l’application. Voici les principaux éléments développés :

### Objectifs :
- Permettre à l’administrateur d’ajouter, modifier et supprimer des activités.
- Lier chaque activité à un lieu spécifique (ville) pour ne proposer aux utilisateurs que les activités disponibles dans leur lieu de séjour.
- Gérer dynamiquement le nombre de participants à chaque planification d'activité.
- Afficher toutes les activités dans le front avec animations et images illustratives.

### Éléments implémentés :
- **Formulaire dynamique de création et modification d’activités** avec sélection du lieu via un `<select>` (Rafraf, Tozeur, Tabarka...).
- **Gestion du nombre de participants (`nbp`)** par planification d’activité.
- **Affichage filtré des activités** sur la base du lieu choisi par l’utilisateur.
- **Ajout de visuels pour chaque activité** via une galerie d’images.
- **Affichage clair et moderne** des détails des activités (nom, description, prix, date, capacité...).

### Technologies utilisées :
- **HTML/CSS** pour le front.
- **PHP (MVC)** pour la logique backoffice.
- **JavaScript (animations et interactions)**.
- **Base de données MySQL** pour stocker les activités, planifications et participants.

---

## 📁 Structure recommandée



