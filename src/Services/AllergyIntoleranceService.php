<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\AllergyIntoleranceFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class AllergyIntoleranceService
{
    /**
     * @var AllergyIntoleranceFHIR
     */
    protected $fhir;

    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var string
     */
    protected $endpoint = '/fhir-r4/v1/AllergyIntolerance';

    /**
     * Constructor
     *
     * @param AllergyIntoleranceFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(AllergyIntoleranceFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get AllergyIntolerance resource by ID
     *
     * @param string $id
     * @return mixed
     */
    public function getById(string $id)
    {
        $endpoint = "{$this->endpoint}/{$id}";
        return $this->satuSehatService->get($endpoint);
    }

    /**
     * Create new AllergyIntolerance resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehatService->post($this->endpoint, $payload);
    }
}
