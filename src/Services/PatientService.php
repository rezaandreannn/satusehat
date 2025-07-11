<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\PatientFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class PatientService
{
    protected $satuSehat;
    protected $fhir;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->fhir = new PatientFHIR();
    }

    /**
     * Cari pasien berdasarkan NIK
     */
    public function searchByNIK(string $nik): array
    {
        $endpoint = '/fhir-r4/v1/Patient';
        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik
        ];
        return $this->satuSehat->get($endpoint, $params);
    }

    /**
     * Cari pasien berdasarkan NIK dan nama
     */
    public function searchByNikAndName(string $nik, string $name): array
    {
        return $this->satuSehat->get('/fhir-r4/v1/Patient', [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
            'name' => $name
        ]);
    }

    /**
     * Cari Pasien berdasarkan IHS Number
     */
    public function searchByIHSNumber(string $ihs): array
    {
        $endpoint = "/fhir-r4/v1/Patient/{$ihs}";
        return $this->satuSehat->get($endpoint);
    }

    /**
     * Tambah pasien baru ke SatuSehat
     */
    public function create(array $data): array
    {
        $endpoint = '/fhir-r4/v1/Patient';
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($endpoint, $payload);
    }

    /**
     * Update data pasien di Satu Sehat
     */
    // public function update(string $ihs, array $data, array $fieldsToUpdate): array
    // {
    //     // dd($fieldsToUpdate);
    //     $endpoint = "/fhir-r4/v1/Patient/{$ihs}";
    //     $payload = $this->fhir->format($data, true);
    //     // dd($payload);
    //     return $this->satuSehat->put($endpoint, $payload);
    // }
}
