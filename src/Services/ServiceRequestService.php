<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\ServiceRequestFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class ServiceRequestService
{
    /**
     * @var ServiceRequestFHIR
     */
    protected $fhir;

    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var string
     */
    protected $endpoint = '/fhir-r4/v1/ServiceRequest';

    /**
     * Constructor
     *
     * @param ServiceRequestFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(ServiceRequestFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get ServiceRequest resource by ID
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
     * Create new ServiceRequest resource
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
