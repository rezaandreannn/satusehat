<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\EncounterFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class EncounterService
{
    protected $satuSehat;
    protected $fhir;
    protected $endpoint;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->fhir = new EncounterFHIR();
        $this->endpoint = '/fhir-r4/v1/Encounter';
    }

    /**
     * Cari Encounter Berdasarkan ID
     */
    public function searchByID($id): array
    {
        $endpoint = "/fhir-r4/v1/Encounter/{$id}";
        return $this->satuSehat->get($endpoint);
    }

    /**
     * Create Encounter 
     * @param $data
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($this->endpoint, $payload);
    }

    /**
     * update encounter
     * @param $data, $id
     */
    public function update(array $data, $id)
    {
        $endpoint = $this->endpoint . '/' . $id;
        $payload = $this->fhir->format($data);
        return $this->satuSehat->put($endpoint, $payload);
    }
}
