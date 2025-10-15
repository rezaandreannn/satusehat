<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\EncounterFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;
use InvalidArgumentException;

class EncounterService
{
    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var EncounterFHIR
     */
    protected $fhir;

    /**
     * @var string
     */
    protected string $endpoint = '/fhir-r4/v1/Encounter';

    /**
     * Constructor
     *
     * @param EncounterFHIR    $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(EncounterFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get Encounter by ID
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
     * Create a new Encounter resource
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
     * Update an existing Encounter resource
     *
     * @param string $id
     * @param array  $data
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function update(string $id, array $data)
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Encounter ID is required for update.');
        }

        $endpoint = "{$this->endpoint}/{$id}";
        $payload = $this->fhir->format($data);

        return $this->satuSehatService->put($endpoint, $payload);
    }
}
