<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\ObservationFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class ObservationService
{
    private $fhir;
    private $satuSehat;
    private $endpoint;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->fhir = new ObservationFHIR();
        $this->endpoint = '/fhir-r4/v1/Observation';
    }

    /**
     * @param $encounter_uuid
     */
    public function searchByEncounter(string $encounter_uuid)
    {
        $param = [
            'encounter' => $encounter_uuid
        ];

        return $this->satuSehat->get($this->endpoint, $param);
    }

    /**
     * @param $id
     */
    public function searchByID($id)
    {
        $endpoint = $this->endpoint . '/' . $id;
        return $this->satuSehat->get($endpoint);
    }

    /**
     * @param $data
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($this->endpoint, $payload);
    }
}
