<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class AllergyIntoleranceFHIR
{
    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'AllergyIntolerance',
        ];

        $formatted += [
            'identifier' => $this->formatIdentifier($data),
            'clinicalStatus' => $this->formatClinicalStatus(),
            'verificationStatus' => $this->formatverificationStatus(),
            'category' => $this->formatCategory(),
            'code' => $this->formatCode($data),
            'patient' => $this->formatPatient($data),
            'encounter' => $this->formatEncounter($data),
            'recordedDate' => $data['recorded_date'],
            'recorder' => $this->formatRecorder($data)
        ];

        return $formatted;
    }

    private function formatIdentifier(array $data)
    {
        return [[
            'system' => 'http://sys-ids.kemkes.go.id/allergy/' . $data['organization_id'],
            'use' => 'official',
            'value' => $data['identifier']
        ]];
    }

    private function formatClinicalStatus(array $data = [])
    {
        $clinicalStatus = $data['clinical_status'] ?? 'active';

        $clinicalStatusMap = [
            'active' => "Active",
        ];

        $clinicalStatusDisplay = $clinicalStatusMap[$clinicalStatus] ?? 'unknown';

        return [
            "coding" => [[
                "system" => "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                "code" => $clinicalStatus,
                "display" => $clinicalStatusDisplay
            ]]
        ];
    }

    private function formatverificationStatus(array $data = [])
    {
        $verificationStatus = $data['verification_status'] ?? 'confirmed';

        $verificationStatusMap = [
            'confirmed' => "Confirmed",
        ];

        $verificationlStatusDisplay = $verificationStatusMap[$verificationStatus] ?? 'unknown';

        return [
            "coding" => [[
                "system" => "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                "code" => $verificationStatus,
                "display" => $verificationlStatusDisplay
            ]]
        ];
    }

    private function formatCategory()
    {
        return [
            'environment'
        ];
    }

    private function formatCode(array $data)
    {
        $result = [
            'coding' => [
                [
                    "system" => "http://snomed.info/sct",
                    "code" => $data['snomed_code'],
                    "display" => $data['snomed_display']
                ]
            ],
            'text' => $data['description']
        ];

        return $result;
    }

    private function formatPatient(array $data)
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

    private function formatRecorder(array $data)
    {
        return [
            "reference" => "Practitioner/" . $data['practitioner_id'],
            "display" => $data['practitioner_name']
        ];
    }
}
