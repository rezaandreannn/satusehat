<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\ConditionFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class ConditionService
{
    /**
     * @var ConditionFHIR
     */
    private $fhir;

    /**
     * @var SatuSehatService
     */
    private $satuSehatService;

    /**
     * @var string
     */
    private $endpoint = '/fhir-r4/v1/Condition';

    /**
     * Constructor
     *
     * @param ConditionFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(ConditionFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Create a new Condition resource
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        $payload = $this->fhir->format($data);

        return $this->satuSehatService->post($this->endpoint, $payload);
    }
}
