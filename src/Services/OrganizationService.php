<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\OrganizationFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;
use InvalidArgumentException;

class OrganizationService
{
    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var OrganizationFHIR
     */
    protected $fhir;

    /**
     * @var string
     */
    protected string $endpoint = '/fhir-r4/v1/Organization';

    /**
     * Constructor
     *
     * @param OrganizationFHIR  $fhir
     * @param SatuSehatService  $satuSehatService
     */
    public function __construct(OrganizationFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get Organization by ID
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
     * Create a new Organization resource
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
     * Update an existing Organization resource
     *
     * @param array $data
     * @return mixed
     */
    public function update(array $data)
    {
        if (empty($data['id'])) {
            throw new InvalidArgumentException('Organization ID is required for update.');
        }

        $payload = $this->fhir->format($data);
        $endpoint = "{$this->endpoint}/{$data['id']}";

        return $this->satuSehatService->put($endpoint, $payload);
    }
}
