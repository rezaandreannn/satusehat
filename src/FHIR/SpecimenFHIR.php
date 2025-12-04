<?php

namespace Rezaandreannn\SatuSehat\FHIR;

use Carbon\Carbon;

class SpecimenFHIR
{
    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'Specimen',
            'status' => 'available',
            'identifier' => $this->formatIdentifier($data),
            'type' => $this->formatType($data),
            'collection' => $this->formatCollection($data),
            'subject' => $this->formatSubject($data),
            'request' => $this->formatRequest($data),
            'receivedTime' => $data['occurrenceDateTime'],
        ];

        return $formatted;
    }

    private function formatIdentifier(array $data)
    {
        return [[
            'system' => 'http://sys-ids.kemkes.go.id/specimen/' . $data['organization_id'],
            'value' => $data['identifier'],
            'assigner' => [
                'reference' => 'Organization/' . $data['organization_id']
            ]
        ]];
    }

    private function formatType(array $data)
    {
        return [
            'coding' => [
                [
                    "system" => "http://snomed.info/sct",
                    "code" => $data['code'], // ?? "119297000", // 119297000 = Blood specimen
                    "display" => $data['display'], // ?? "Blood specimen (specimen)"
                ]
            ]
        ];
    }

    private function formatCollection(array $data)
    {
        return [
            // Collector (Pemeriksa Lab)
            'collector' => [
                'reference' => 'Practitioner/' . $data['practitioner_pemeriksa_id'],
                'display' => $data['practitioner_pemeriksa_name']
            ],
            // Waktu pengambilan
            // 'collectedDateTime' => $data['collectedDateTime'],
            // Kuantitas (Perlu data tambahan, diasumsikan darah 10 mL)


        ];
    }



    private function formatSubject(array $data)
    {
        return [
            "reference" => "Patient/" . $data['patient_id'],
            "display"   => $data['patient_name']
        ];
    }

    private function formatRequest(array $data)
    {
        return [
            [
                "reference" => "ServiceRequest/" . $data['servicerequest_uuid']
            ]
        ];
    }
}
