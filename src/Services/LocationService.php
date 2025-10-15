<?php

namespace Rezaandreannn\SatuSehat\Services;

use Illuminate\Support\Arr;
use Rezaandreannn\SatuSehat\FHIR\LocationFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class LocationService
{
    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var LocationFHIR
     */
    protected $fhir;

    /**
     * @var string
     */
    protected $endpoint = '/fhir-r4/v1/Location';

    /**
     * Constructor
     *
     * @param LocationFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(LocationFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get Location by ID
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
     * Get Locations by Organization ID
     *
     * @param string $organizationId
     * @return mixed
     */
    public function getByOrganizationId(string $organizationId)
    {
        $params = [
            'organization' => $organizationId,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Create a new Location resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehatService->post($this->endpoint, $payload);
    }

    /**
     * Update an existing Location resource
     *
     * @param array $data
     * @return mixed
     */
    public function update(array $data)
    {
        $payload = $this->fhir->format($data);
        $endpoint = "{$this->endpoint}/{$data['id']}";

        return $this->satuSehatService->put($endpoint, $payload);
    }
}
