<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\ConditionFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class ConditionService
{
    private $fhir, $endpoint, $satuSehatService;

    public function __construct(ConditionFHIR $fhir)
    {
        $this->fhir = $fhir;
        $this->endpoint = "/fhir-r4/v1/Condition";
        $this->satuSehatService = new SatuSehatService();
    }

    public function create($data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehatService->post($this->endpoint, $payload);
    }
}
