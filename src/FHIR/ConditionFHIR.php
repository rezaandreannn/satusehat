<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class ConditionFHIR
{

    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'Condition',
        ];

        switch ($data['type']) {
            case 'primary-diagnosis':
            case 'secondary-diagnosis':

                $formatted += [
                    "clinicalStatus" => $this->formatClinicalStatus(),
                    "category" => $this->formatCategory($data),
                    "code" => $this->formatcode($data),
                    "subject" => $this->formatSubject($data),
                    "encounter" => $this->formatEncounter($data),
                    "onsetDateTime" => $data['onsetDateTime'],
                    "recordedDate" => $data['recordedDate']
                ];
                break;

            default:
                # code...
                break;
        }

        return $formatted;
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
                "system" => "http://terminology.hl7.org/CodeSystem/condition-clinical",
                "code" => $clinicalStatus,
                "display" => $clinicalStatusDisplay
            ]]
        ];
    }

    private function formatCategory(array $data)
    {
        $categorycode = $data['category_code'] ?? 'encounter-diagnosis';

        $cateogryMap = [
            'chief-complaint' => "Chief Complaint",
            'encounter-diagnosis' => "Encounter Diagnosis"
        ];

        $categoryDisplay = $cateogryMap[$categorycode] ?? 'unknown';

        $category = [[
            "coding" => [[
                "system" => "http://terminology.hl7.org/CodeSystem/condition-category",
                "code" => $categorycode,
                "display" => $categoryDisplay
            ]]
        ]];

        return $category;
    }

    private function formatcode(array $data)
    {
        $text = null;

        if ($data['type'] == 'primary-diagnosis') {
            $text = "Diagnosis primer " . $data['icd-10-en'];
        } else if ($data['type'] == 'secondary-diagnosis') {
            $text = "Diagnosis sekunder " . $data['icd-10-en'];
        }

        // Bangun array hasil FHIR code
        $result = [
            "coding" => [
                [
                    "system" => "http://hl7.org/fhir/sid/icd-10",
                    "code" => $data['icd-10-code'],
                    "display" => $data['icd-10-en']
                ]
            ]
        ];

        if ($text) {
            $result["text"] = $text;
        }

        return $result;
    }


    private function formatSubject(array $data)
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

    private function formatPerformer(array $data)
    {
        return [
            "reference" => "Practitioner/" . $data['practitioner_id'],
            "display" =>  $data['practitioner_name']
        ];
    }

    private function formatNote(array $data)
    {
        return [[
            "text" => $data['text']
        ]];
    }
}
