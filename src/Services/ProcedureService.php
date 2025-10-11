<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\ProcedureFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class ProcedureService
{
    protected $fhir, $endpoint, $satuSehatService;

    public function __construct(ProcedureFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
        $this->endpoint = '/fhir-r4/v1/Procedure';
    }

    public function createDiasnoticProcedure(array $data)
    {
        $data['category'] = 'Diagnostic procedure';

        $payload = $this->fhir->format($data);
        return $this->satuSehatService->post($this->endpoint, $payload);
    }
}
