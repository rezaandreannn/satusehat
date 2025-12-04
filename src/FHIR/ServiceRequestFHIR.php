<?php

namespace Rezaandreannn\SatuSehat\FHIR;

use Carbon\Carbon;

class ServiceRequestFHIR
{
    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'ServiceRequest',
            'status' => $data['status'] ?? 'active',
            'intent' => $this->formatIntent(),
            'priority' => $this->formatPriority(),
            'identifier' => $this->formatIdentifier($data),
            'category' => $this->formatCategory(),
            'code' => $this->formatCode($data),
            'subject' => $this->formatPatient($data),
            'encounter' => $this->formatEncounter($data),
            'occurrenceDateTime' => $data['occurrenceDateTime'],
            'requester' => $this->formatRequester($data),
            'performer' => $this->formatPerformer($data),
        ];

        return $formatted;
    }

    private function formatIdentifier(array $data)
    {
        // dd($data);
        return [[
            'system' => 'http://sys-ids.kemkes.go.id/servicerequest/' . $data['organization_id'],
            'value' => $data['identifier']
        ]];
    }

    private function formatIntent()
    {
        return "order";
    }

    private function formatPriority()
    {
        return "routine";
    }

    private function formatCategory()
    {
        return [[
            'coding' => [
                [
                    "system" => "http://snomed.info/sct",
                    "code" => "108252007",
                    "display" => "Laboratory procedure"
                ]
            ]
        ]];
    }

    // Dokter Pemeriksa Lab
    private function formatPerformer(array $data)
    {
        return [
            [
                "reference" => "Practitioner/" . $data['practitioner_pemeriksa_id'],
                "display"   => $data['practitioner_pemeriksa_name']
            ]
        ];
    }

    private function formatCode(array $data)
    {
        return [
            'coding' => [
                [
                    "system"  => "http://loinc.org",
                    "code"    => $data['code'],
                    "display" => $data['display']
                ]
            ],
            'text' => $data['pemeriksaan']
        ];
    }

    private function formatPatient(array $data)
    {
        return [
            "reference" => "Patient/" . $data['patient_id'],
            "display"   => $data['patient_name']
        ];
    }

    private function formatEncounter(array $data)
    {
        return [
            "reference" => "Encounter/" . $data['encounter_uuid']
        ];
    }

    // Dokter Pengirim
    private function formatRequester(array $data)
    {
        return [
            "reference" => "Practitioner/" . $data['practitioner_pengirim_id'],
            "display"   => $data['practitioner_pengirim_name']
        ];
    }
}
