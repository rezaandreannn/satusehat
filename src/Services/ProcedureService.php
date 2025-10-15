<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\ProcedureFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class ProcedureService
{
    /**
     * @var ProcedureFHIR
     */
    protected $fhir;

    /**
     * @var SatuSehatService
     */
    protected $satuSehatService;

    /**
     * @var string
     */
    protected $endpoint = '/fhir-r4/v1/Procedure';

    /**
     * Constructor
     *
     * @param ProcedureFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(ProcedureFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get Observation resources by Encounter UUID
     *
     * @param string $encounterUuid
     * @return mixed
     */
    public function getByEncounter(string $encounterUuid)
    {
        $params = [
            'encounter' => $encounterUuid,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Get Observation resource by ID
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
     * Create a new Diagnostic Procedure resource
     *
     * @param array $data
     * @return mixed
     */
    public function createDiagnosticProcedure(array $data)
    {
        $data['category'] = 'Diagnostic procedure';

        $payload = $this->fhir->format($data);
        return $this->satuSehatService->post($this->endpoint, $payload);
    }
}
