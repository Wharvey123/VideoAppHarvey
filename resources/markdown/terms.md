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

### Sprint 4: Gestió de Vídeos

- **Correcció d'accés:**
    - Ajustar accés a `/videos/manage` segons permisos.
- **Controlador `VideosManageController`:**
    - Desenvolupament de mètodes `testedBy`, `index`, `create`, `store`, `show`, `edit`, `update`, `delete`, `destroy`.
- **Mètode `index`:**
    - Afegit a `VideosController` per la pàgina pública.
- **Vídeos per defecte:**
    - Verificació de la creació de 3 vídeos per defecte amb `VideoHelper` i `DatabaseSeeder`.
- **Vistes CRUD:**
    - Creació de vistes (index, create, edit, delete) per a la gestió de vídeos.
- **Rutes:**
    - Definició de rutes CRUD amb middleware d'autenticació.
- **Assignació de permisos:**
    - Assignació de permisos específics als usuaris.
- **Tests:**
    - Desenvolupament de tests seguint TDD i el patró AAA.
- **Interfície d'usuari:**
    - Afegida la navegació (Navbar i Footer) a la plantilla principal.

### Sprint 5: Gestió d’Usuaris

- Creació del controlador **UsersManageController** amb les funcions: testedBy, index, store, edit, update, delete i destroy.
- Creació del controlador **UsersController** amb les funcions index i show.
- Creació de vistes per al CRUD d’usuaris (index, create, edit, delete) per usuaris amb permisos.
- Creació de la vista pública **users/index.blade.php** amb cerca i accés al detall.
- Actualització dels helpers per afegir els permisos de gestió d’usuaris i assignació als superadmins.
- Desenvolupament de tests per comprovar l’accés segons els permisos:
    - En **UserTest:** Tests per la visualització per defecte i del detall d’usuaris.
    - En **UsersManageControllerTest:** Tests per la gestió (crear, editar, eliminar) segons els rols.
- Creació de les rutes per a la gestió d’usuaris amb middleware d’autenticació.
- Afegida la navegació entre pàgines i actualització de la documentació.

### Sprint 6: Gestió de Sèries i Assignació de Vídeos a Sèries

**Funcionalitats implementades:**
- Creació de la migració per a la taula `series`.
- Creació del model `Serie` amb les relacions i accessors.
- Actualització del model `Video` per afegir la relació amb `Serie`.
- Desenvolupament dels controladors `SeriesController` (pública) i `SeriesManageController` (CRUD).
- Creació d'un helper (`SeriesHelper`) per generar 3 sèries per defecte.
- Creació de vistes per al CRUD de sèries i la visualització pública.
- Definició de rutes per a la gestió de sèries.
- Implementació de tests utilitzant TDD/AAA per comprovar la funcionalitat.
- Assignació i creació de permisos per a la gestió de sèries.

### Sprint 7: Correccions, notificacions i broadcast

**Correccions i millores:**
- Correcció d'errors detectats al **Sprint 6**.
- Revisió i reparació de tests d'sprints anteriors afectats per modificacions recents.
- Implementació de la funcionalitat perquè els **Regular Users** puguin crear sèries i afegir-hi vídeos.

**Notificacions i Broadcasting:**
- Creació de l’**event `VideoCreated`**, amb constructor, `broadcastOn()` i `broadcastAs()`, assegurant que implementa `ShouldBroadcast`.
- Disparar l’event `VideoCreated` des del controlador en el moment de crear un vídeo.
- Creació del **listener `SendVideoCreatedNotification`**, amb el mètode `handle(VideoCreated $event)` per:
    - Enviar un correu electrònic als administradors.
    - Llençar la notificació `VideoCreated` amb informació del vídeo.
- Actualització del fitxer `app/Providers/EventServiceProvider.php` per registrar l’event i el listener corresponent.

**Configuració de serveis externs:**
- **Mailtrap/Mailchimp**: Registre i configuració del `.env` per poder enviar correus des de Laravel.
- **Pusher**: Registre, configuració de credencials al `.env` i comprovació a `config/broadcasting.php` perquè Pusher sigui el servei per defecte.

**Laravel Echo i Notificacions Push:**
- Instal·lació de `laravel-echo` i `pusher-js` via `npm`.
- Configuració de Laravel Echo al fitxer `resources/js/bootstrap.js`.
- Creació d’una vista per mostrar les notificacions push en temps real.
- Creació de la ruta per accedir a les notificacions.

**Tests:**
- Creació de `VideoNotificationsTest` amb les funcions:
    - `test_video_created_event_is_dispatched()`
    - `test_push_notification_is_sent_when_video_is_created()`

**Documentació i qualitat de codi:**
- Actualització d’aquest document (`terms.md`) amb totes les funcionalitats del Sprint 7.
- Verificació de qualitat i estàtica del codi amb **Larastan** per assegurar que els fitxers creats compleixen les bones pràctiques.

---


Gràcies per utilitzar VideosApp!
