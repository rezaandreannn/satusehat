<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\PatientFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class PatientService
{
    protected $satuSehat;
    protected $fhir;
    protected $endpoint;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->fhir = new PatientFHIR();
        $this->endpoint = '/fhir-r4/v1/Patient';
    }

    /**
     * Cari pasien berdasarkan NIK
     * @param $nik
     */
    public function searchByNIK(string $nik): array
    {
        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik
        ];
        return $this->satuSehat->get($this->endpoint, $params);
    }

    /**
     * Cari pasien berdasarkan NIK dan name
     * @param $nik, $name
     */
    public function searchByNikAndName(string $nik, string $name): array
    {
        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
            'name' => $name
        ];
        return $this->satuSehat->get($this->endpoint, $params);
    }

    /**
     * Cari pasien berdasarkan NIK, Name, Birthdate
     * @param $nik, $name, $birthdate
     */
    public function searchByNikNameBirthdate(string $nik, string $name, string $birthdate): array
    {
        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
            'name' => $name,
            'birthdate' => $birthdate
        ];
        return $this->satuSehat->get($this->endpoint, $params);
    }

    /**
     * Cari pasien berdasarkan Name, Birthdate, Gender
     * @param $name, $birthdate, $gender
     */
    public function searchByNameBirthdateGender(string $name, string $birthdate, string $gender): array
    {
        $params = [
            'name' => $name,
            'birthdate' => $birthdate,
            'gender' => $gender
        ];
        return $this->satuSehat->get($this->endpoint, $params);
    }

    /**
     * Cari pasien bayi berdasarkan NIK Ibu
     * @param $nikIbu, $birthdateBayi
     */
    public function searchBayiByNIKIbu(string $nikIbu, string $birthdateBayi): array
    {
        $params = [
            "identifier" => 'https://fhir.kemkes.go.id/id/nik-ibu|' . $nikIbu,
            "birthdate" => $birthdateBayi
        ];

        return $this->satuSehat->get($this->endpoint, $params);
    }

    /**
     * Cari Pasien berdasarkan IHS Number
     * @param $ihs
     */
    public function searchByIHSNumber(string $ihs): array
    {
        $endpoint = $this->endpoint . '/' . $ihs;
        return $this->satuSehat->get($endpoint);
    }

    /**
     * Tambah pasien baru ke SatuSehat
     * @param $data
     */
    public function createByNik(array $data): array
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($this->endpoint, $payload);
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
