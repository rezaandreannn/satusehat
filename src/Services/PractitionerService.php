<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\SatuSehatService;

class PractitionerService
{
    protected $satuSehat;
    protected $endpoint;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->endpoint = '/fhir-r4/v1/Practitioner';
    }

    /**
     * Cari Practitioner Berdasarkan NIK
     */
    public function searchByNIK(string $nik): array
    {
        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik
        ];
        return $this->satuSehat->get($this->endpoint, $params);
    }

    /**
     * Cari Practitioner Berdasarkan NIK dan Name
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
     * Cari Practitioner Berdasarkan Name, Gender, dan birthdate
     */
    public function searchByNameGenderBirthdate(string $name, string $gender, string $birthdate): array
    {
        $params = [
            'name' => $name,
            'gender' => $gender,
            'birthdate' => $birthdate
        ];

        return $this->satuSehat->get($this->endpoint, $params);
    }
}
