<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\SpecimenFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class SpecimenService
{
    /**
     * @var SpecimenFHIR
     */
    protected $fhir;

    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var string
     */
    protected $endpoint = '/fhir-r4/v1/Specimen';

    /**
     * Constructor
     *
     * @param SpecimenFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(SpecimenFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get Specimen resource by ID
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
     * Create new Specimen resource
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
