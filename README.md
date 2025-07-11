# Laravel Satu Sehat Integration

## 📦 Instalasi

```bash
composer require rezaandreannn/satusehat
```

## Publish Config dan migration

```bash
php artisan vendor:publish --provider="Rezaandreannn\SatuSehat\SatuSehatServiceProvider"
```

## ⚙️ Konfigurasi

```bash
SATUSEHAT_ENV=your_env #Environment: sandbox atau production
SATUSEHAT_CLIENT_ID=your_client_id
SATUSEHAT_CLIENT_SECRET=your_client_secret
SATUSEHAT_ORGANIZATION_ID=your_organization_id
```

## Basic Usage

### Patient Service

```bash
use Rezaandreannn\SatuSehat\Services\PatientService;

$patient = new PatientService();
# Get By NIK
$nik = 'your_nik';
$result = $patient->searchByNIK($nik);

# Get By NIK And Name
$nik = 'your_nik';
$name = 'your_name';
$result = $patient->searchByNikAndName($nik, $name);

# Get By IHS Number
$ihsNumber = 'your_ihs_number'
$result = $patient->searchByIHSNumber($ihsNumber);
```
