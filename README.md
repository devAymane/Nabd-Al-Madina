# 🏙️ Nabd Al Madina - API Backend

Plateforme de gestion des signalements urbains avec analyse automatique par IA (catégorisation, priorité, département).

---

## Fonctionnalités

- **Auth:** Inscription/Connexion (Laravel Sanctum).
- **Signalements (US7/US8):** Création, upload photo, géolocalisation, et **analyse automatique par IA**.
- **Départements:** Gestion et affectation.
- **Incidents:** Regroupement et blocage de suppression des incidents liés (`403 Forbidden`).

---

##  Tech Stack

- **Framework:** Laravel 11.x
- **Database:** MySQL
- **Auth:** Sanctum
- **IA Integration:** `SignalementAnalyzer`

---

## Installation Rapide

1. **Cloner & installer:**
   ```bash
   git clone [https://github.com/votre-username/nabd_al_madina.git](https://github.com/votre-username/nabd_al_madina.git)
   cd nabd_al_madina
   composer install

  ** Configuration .env:

  cp .env.example .env
php artisan key:generate

** Base de données & Serveur:

php artisan migrate --seed
php artisan storage:link
php artisan serve

 ** Endpoints Clés
POST /api/login & /api/register

GET|POST /api/signalements

PATCH /api/signalements/{id}/status

GET|POST /api/incidents

DELETE /api/incidents/{id} (Sécurisé)