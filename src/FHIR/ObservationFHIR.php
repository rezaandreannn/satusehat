<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class ObservationFHIR
{
    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'Observation',
            'status' => $data['status'] ?? 'final'
        ];

        $formatted += [
            'category' => $this->formatCategory($data),
            'code' => $this->formatCode($data),
            'subject' => $this->formatSubject($data),
            'encounter' => $this->formatEncounter($data),
            'effectiveDateTime' => $data['effective_DateTime'],
            'issued' => $data['issued'],
            'performer' => $this->formatPerformer($data),
            "valueQuantity" => $this->formatValueQuantity($data)
        ];

        return $formatted;
    }

    private function formatCategory(array $data)
    {
        $categorycode = $data['category_code'] ?? 'vital-signs';

        $cateogryMap = [
            'vital-signs' => "Vital Sign"
        ];

        $categoryDisplay = $cateogryMap[$categorycode] ?? 'unknown';

        $category = [[
            "coding" => [[
                "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                "code" => $categorycode,
                "display" => $categoryDisplay
            ]]
        ]];

        return $category;
    }

    private function formatCode(array $data)
    {
        $code = $data['code'] ?? '8310-5';

        $codeMap = [
            '8310-5' => "Body temperature",
            '8867-4' => "Heart rate",
            '9279-1' => "Respiratory rate",
            '8462-4' => "Diastolic blood pressure",
            '8480-6' => "Systolic blood pressure"
        ];

        $codeDisplay = $codeMap[$code] ?? 'unknown';

        $code = [
            "coding" => [[
                "system" => "http://loinc.org",
                "code" => $code,
                "display" => $codeDisplay
            ]]
        ];

        return $code;
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
        return [[
            "reference" => "Practitioner/" . $data['practitioner_id'],
            "display" =>  $data['practitioner_name']
        ]];
    }

    private function formatValueQuantity(array $data)
    {
        switch ($data['code']) {
            case '8310-5':
                $result = [
                    "value" => $data["value"],
                    "unit" => "cel",
                    "system" => "http://unitsofmeasure.org",
                    "code" =>  "Cel"
                ];
                break;
            case '8867-4':
                $result = [
                    "value" => $data["value"],
                    "unit" => "{beats}/min",
                    "system" => "http://unitsofmeasure.org",
                    "code" =>  "{beats}/min"
                ];
                break;
            case '9279-1':
                $result = [
                    "value" => $data["value"],
                    "unit" => "breaths/min",
                    "system" => "http://unitsofmeasure.org",
                    "code" =>  "/min"
                ];
                break;
            case '8480-6':
                $result = [
                    "value" => $data["value"],
                    "unit" => "mm[Hg]",
                    "system" => "http://unitsofmeasure.org",
                    "code" =>  "mm[Hg]"
                ];
                break;
            case '8462-4':
                $result = [
                    "value" => $data["value"],
                    "unit" => "mm[Hg]",
                    "system" => "http://unitsofmeasure.org",
                    "code" =>  "mm[Hg]"
                ];
                break;

            default:
                # code...
                break;
        }

        return $result;
    }
}
