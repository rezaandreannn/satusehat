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

<!-- ## Basic Usage -->

<!-- ### Patient Service -->

<!-- ```bash
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

# Post Patient To SatuSehat
 $data = [
        "nik" => "your_nik",
        "name" => "John Smith 11",
        "gender" => "female",
        "birthDate" => "1945-11-17",
        "alamat" => "Gd. Prof. Dr. Sujudi Lt.5, Jl. H.R. Rasuna Said",
        "postalCode" => "12950",

        # Master Data API - APIGEE (v2.0) (PUBLIC)
        "city" => "Jakarta",
        "provinceCode" => "31",
        "cityCode" => "3174",
        "districtCode" => "317406",
        "villageCode" => "3174061001",
        "rt" => "02",
        "rw" => "02",

        "mobile" => "your_mobile",
        "phone" => "your_phone",
        "email" => "your_email@xyza.com",
        "maritalStatus" => "M",
        "maritalStatusText" => "Married",

        # Relashionship
        "contactName" => "Jane Smith",
        "contactPhone" => "0690383372",
        "contactRelationship" => "C",

        "birthPlaceCity" => "Jakarta",
        "citizenship" => "WNI"
    ];
$result = $patient->create($data);
``` -->
