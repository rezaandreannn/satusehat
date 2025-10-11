<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class ProcedureFHIR
{
    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'Procedure',
        ];

        $formatted += [
            'status' => $data['status'],
            'category' => $this->formatCategory($data),
            'code' => $this->formatCode($data),
            'subject' => $this->formatSubject($data),
            'encounter' => $this->formatEncounter($data),
            'performedPeriod' => $this->formatperformedPeriod($data),
            'performer' => $this->formatPerformer($data)
        ];

        return $formatted;
    }

    private function formatBaseOn() {}

    private function formatCategory(array $data)
    {
        switch ($data['category']) {
            case 'Diagnostic procedure':
                $result = [
                    'coding' => [
                        [
                            "system" => "http://terminology.kemkes.go.id",
                            "code" => 'TK000028',
                            "display" => "Diagnostic procedure"
                        ],
                    ],
                    'text' => 'Prosedur Diagnostik'
                ];
                break;

            default:
                # code...
                break;
        }
        return $result;
    }

    private function formatCode(array $data)
    {
        $result = [
            'coding' => [
                [
                    "system" => "http://hl7.org/fhir/sid/icd-9-cm",
                    "code" => $data['icd-9-code'],
                    "display" => $data['icd-9-en']
                ],
                [
                    "system" => "http://snomed.info/sct",
                    "code" => $data['snomed-code'],
                    "display" => $data['snomed-display']
                ]
            ]
        ];

        return $result;
    }

    private function formatSubject(array $data)
    {
        return [
            "reference" => "Patient/" . $data['patient_id'],
            "display" => $data['patient_name']
        ];
    }

    private function formatEncounter(array $data)
    {
        return [
            "reference" => "Encounter/" . $data['encounter_uuid']
        ];
    }

    private function formatperformedPeriod(array $data)
    {
        return [
            "end" => $data['end'],
            "start" =>  $data['start']
        ];
    }

    private function formatPerformer(array $data)
    {
        return [[
            'actor' => [
                "reference" => "Practitioner/" . $data['practitioner_id'],
                "display" => $data['practitioner_name']
            ]
        ]];
    }
}
