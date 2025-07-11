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
