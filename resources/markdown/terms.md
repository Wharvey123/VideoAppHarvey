# Guia del projecte VideosApp

## Descripció del projecte
**VideosApp** és una aplicació desenvolupada amb Laravel que permet gestionar vídeos educatius. L'objectiu és oferir una plataforma senzilla però potent per a organitzar i visualitzar vídeos, amb funcionalitats que inclouen la navegació entre vídeos, la gestió d'usuaris i equips, i una interfície responsive per a una millor experiència d'usuari.

El projecte està estructurat seguint una metodologia àgil basada en sprints, on cada sprint incorpora funcionalitats específiques al sistema.

---

## Tasques realitzades

### Sprint 1: Creació inicial del projecte

- **Configuració del projecte:**
    - Creació del projecte Laravel amb Jetstream, Livewire, PHPUnit, equips i SQLite.
- **Creació de tests inicials:**
    - Test per verificar la creació dels usuaris per defecte (usuari i professor).
- **Helpers:**
    - Creació d'un helper per a la gestió d'usuaris per defecte.
- **Configuració:**
    - Afegir credencials per defecte a `config` i associar-les al fitxer `.env`.

### Sprint 2: Gestió de vídeos i funcionalitats avançades

- **Configuració de tests:**
    - Modificació del fitxer `phpunit.xml` per utilitzar una base de dades temporal durant els tests.
- **Migracions:**
    - Creació de la taula `videos` amb els camps necessaris (id, title, description, url, published_at, etc.).
- **Model de vídeos:**
    - Implementació de funcionalitats per formatar les dates (`getFormattedPublishedAtAttribute`, etc.).
- **Controlador de vídeos:**
    - Funcions `testedBy` i `show` per gestionar els vídeos.
- **Helpers:**
    - Creació d'un helper per generar vídeos per defecte.
- **Seeders:**
    - Inserció d'usuaris i vídeos per defecte a la base de dades amb el `DatabaseSeeder`.
- **Interfície d'usuari:**
    - Creació d'un layout responsive i la vista del detall dels vídeos.
- **Tests de vídeos:**
    - Verificació de la formatació de dates i de la visualització dels vídeos.
- **Documentació:**
    - Creació d'aquesta guia i explicació del treball realitzat fins ara.

### Sprint 3: Gestió de permisos i programació de tasques

- **Gestió de permisos:**
    - Implementació del paquet `spatie/laravel-permission` per a la gestió de rols i permisos.
    - Creació dels rols Super Admin, Video Manager i Regular User.
    - Assignació de permisos específics a cada rol, com ara `manage videos` per al Video Manager.
- **Programació de tasques:**
    - Configuració de tasques programades utilitzant el planificador de Laravel per a operacions periòdiques, com ara la neteja de vídeos antics.
- **Tests de permisos:**
    - Creació de tests per assegurar que només els usuaris amb els permisos adequats poden gestionar vídeos.
- **Millores en la interfície d'usuari:**
    - Afegir indicadors visuals per mostrar els permisos de l'usuari actual.
    - Millorar la navegació per a una millor experiència d'usuari.
- **Documentació:**
    - Actualització de la guia del projecte per incloure les noves funcionalitats i instruccions per a la gestió de permisos i programació de tasques.

---

## Properes passes
En els següents sprints, es preveu implementar funcionalitats més avançades com la gestió de sèries de vídeos, cerca, i funcionalitats socials com comentaris o valoracions.

---

Gràcies per utilitzar VideosApp!
