# Ihre-Stelle Jobboard

Ein modernes Laravel-basiertes Jobboard mit Airtable-Integration, Mapbox-Karten und Express-Bewerbungssystem.

## 🚀 Features

- **Airtable-Integration**: Automatische Synchronisation von Jobs aus Airtable
- **Mapbox-Karten**: Interaktive Karten für Job-Standorte
- **Express-Bewerbungen**: Direktes Bewerbungssystem mit Datei-Upload
- **Responsive Design**: Moderne UI mit Ihre-Stelle Branding
- **Automatische Synchronisation**: Stündliche Updates via Laravel Scheduler
- **Webhook-Support**: Sofortige Updates einzelner Jobs
- **SEO-optimiert**: Saubere URLs und Meta-Tags

## 📋 Voraussetzungen

- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Airtable Account
- Mapbox Account

## 🛠 Installation

### 1. Repository klonen
```bash
git clone <repository-url>
cd ihre-stelle
```

### 2. Dependencies installieren
```bash
composer install
npm install
```

### 3. Environment konfigurieren
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Datenbank konfigurieren
Bearbeite `.env` mit deinen Datenbankdaten:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ihre_stelle
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Datenbank migrieren
```bash
php artisan migrate:fresh
```

### 6. Assets kompilieren
```bash
npm run build
```

## ⚙️ Konfiguration

### Airtable-Integration

Füge folgende Variablen zu deiner `.env` hinzu:

```env
AIRTABLE_API_KEY=your_airtable_api_key
AIRTABLE_BASE_ID=your_base_id
AIRTABLE_JOBS_TABLE=Jobs
AIRTABLE_KANDIDATEN_TABLE=Kandidaten
```

**Airtable-Tabellen Setup:**

#### Jobs-Tabelle
Erforderliche Felder:
- Job Titel (Single line text)
- Anrede (Single line text)
- Job beschreibung (Long text)
- Status (Single select: "Live", "Entwurf", etc.)
- Kategorie (Single line text)
- Stadt (Single line text)
- PLZ_Job (Single line text)
- longitude (Number)
- latitude (Number)
- Arbeitsgeber Name (Single line text)
- Arbeitsgeber Tel (Multiple select)
- Arbeitsgeber Website (Multiple select)
- JobTyp (Multiple select)
- Autotags (Multiple select)
- Benefits (Multiple select)
- Rolle im Job (Multiple select)
- Berufserfahrung (Single line text)
- Schulabschluss (Single line text)
- Job Logo (Attachment)
- Banner FB (Attachment)
- Ihre-StelleLink (Single line text) - wird automatisch befüllt

#### Kandidaten-Tabelle
Erforderliche Felder:
- Name (Single line text)
- Vorname(PG) (Single line text)
- Mail von Bewerber/-in (Email)
- Telephone (Phone number)
- Nachricht (Long text)
- Link to Jobs (Link to another record)
- Status (Single select: "Ready", "Contacted", etc.)
- Quelle (Single line text)
- Unterlagen (Attachment)

### Mapbox-Integration

```env
MAPBOX_ACCESS_TOKEN=pk.eyJ1IjoiYmVrc2VydmljZSIsImEiOiJjazl2NnB3bjAwOG82M3BydWxtazQyczdkIn0.w_HtU8Vi9uRDtZa0Qy3FqA
```

### Mail-Konfiguration

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ihre-stelle.de
MAIL_FROM_NAME="Ihre-Stelle"
```

## 🔄 Synchronisation

### Automatische Synchronisation

Das System synchronisiert automatisch stündlich alle Jobs aus Airtable:

```bash
# Scheduler starten (für lokale Entwicklung)
php artisan schedule:work

# Für Produktion: Cron-Job einrichten
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Manuelle Synchronisation

```bash
# Alle Jobs synchronisieren
php artisan airtable:sync

# Einzelnen Job synchronisieren
php artisan airtable:sync-job {airtable_record_id}
```

## 🔗 Webhook-Integration

### Webhook-URL für Airtable Automations

```
POST https://ihre-stelle.de/webhook/sync-job
Content-Type: application/json

{
    "id": "airtable_record_id"
}
```

**Airtable Automation Setup:**
1. Gehe zu Automations in deiner Airtable Base
2. Erstelle neue Automation mit Trigger "When record updated"
3. Füge Action "Send webhook" hinzu
4. URL: `https://ihre-stelle.de/webhook/sync-job`
5. Method: POST
6. Body: `{"id": "{Record ID}"}`

### Webhook für Job-Updates

Wenn ein Job in Airtable aktualisiert wird, wird automatisch:
- Der Job in der Laravel-Datenbank aktualisiert
- Das Feld "Ihre-StelleLink" in Airtable mit der korrekten URL befüllt

## 🎨 Design & Branding

### Ihre-Stelle Farben

```css
/* Primary Colors */
--primary-orange: #C4704A;
--primary-blue: #4A5568;

/* Accent Colors */
--accent-orange: #ED8936;
--accent-blue: #3182CE;
```

### Logo-Dateien

- Logo: `/public/logo/ihre-stelle_logo_quer-logo.png`
- Favicon: `/public/favicon.ico`

## 📱 Seiten & Routen

### Öffentliche Seiten
- **Homepage**: `/` - Übersicht mit Featured Jobs
- **Job-Suche**: `/jobs/search` - Suche mit Filtern und Karte
- **Job-Details**: `/jobs/{slug}` - Einzelne Job-Ansicht
- **Bewerbung**: `/jobs/{slug}/bewerben` - Express-Bewerbungsformular
- **Erfolg**: `/jobs/{slug}/bewerbung-erfolgreich` - Bestätigungsseite

### Rechtliche Seiten
- **Impressum**: `/impressum`
- **Datenschutz**: `/datenschutz`

### API-Endpunkte
- **Webhook**: `POST /webhook/sync-job`
- **Sitemap**: `/sitemap.xml`

## 🗂 Dateistruktur

```
ihre-stelle/
├── app/
│   ├── Console/Commands/
│   │   ├── SyncAirtableJobs.php      # Haupt-Sync-Command
│   │   └── SyncSingleJob.php         # Einzelner Job-Sync
│   ├── Http/Controllers/
│   │   ├── JobController.php         # Job-Anzeige und Suche
│   │   ├── ApplicationController.php # Bewerbungssystem
│   │   └── WebhookController.php     # Webhook-Handler
│   └── Models/
│       ├── JobPost.php               # Job-Model mit Airtable-Mapping
│       └── Application.php           # Bewerbungs-Model
├── resources/
│   ├── views/
│   │   ├── jobs/                     # Job-Templates
│   │   ├── applications/             # Bewerbungs-Templates
│   │   └── legal/                    # Rechtliche Seiten
│   └── css/
│       └── ihre-stelle-styles.css    # Custom Styles
└── public/
    ├── logo/                         # Logo-Dateien
    └── css/
        └── ihre-stelle-styles.css    # Fallback CSS
```

## 🚀 Deployment

### cPanel Deployment

1. **Dateien hochladen**:
   - Alle Dateien außer `/public` in einen Ordner (z.B. `ihre-stelle`)
   - Inhalt von `/public` in das Document Root

2. **Symlinks erstellen**:
   ```bash
   ln -s ../ihre-stelle/storage/app/public public/storage
   ```

3. **Environment**:
   - `.env` Datei mit Produktionsdaten erstellen
   - `APP_ENV=production` setzen
   - `APP_DEBUG=false` setzen

4. **Datenbank**:
   ```bash
   php artisan migrate:fresh
   ```

5. **Assets**:
   - Build-Ordner ist bereits im Git enthalten
   - Fallback CSS ist verfügbar unter `/css/ihre-stelle-styles.css`

6. **Cron-Job einrichten**:
   ```bash
   * * * * * cd /home/username/ihre-stelle && php artisan schedule:run >> /dev/null 2>&1
   ```

### Wichtige Produktions-Einstellungen

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ihre-stelle.de

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Logs
LOG_CHANNEL=single
LOG_LEVEL=error
```

## 🔧 Wartung

### Cache leeren
```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### Logs überprüfen
```bash
tail -f storage/logs/laravel.log
```

### Sync-Status überprüfen
```bash
php artisan airtable:sync --dry-run
```

## 📞 Support & Kontakt

**BEK Service GmbH**
- Website: https://ihre-stelle.de
- E-Mail: info@bekservice.de
- Telefon: (+49) 831 93065616
- Adresse: Westendstr. 2A, D-87439 Kempten (Allgäu)

## 📄 Lizenz

Proprietary - BEK Service GmbH

---

**Letzte Aktualisierung**: Januar 2025
**Laravel Version**: 12.17.0
**PHP Version**: 8.3+
