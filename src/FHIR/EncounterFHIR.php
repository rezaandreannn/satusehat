<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class EncounterFHIR
{
    public function format(array $data): array
    {
        $formatted = [
            'resourceType' => 'Encounter',
        ];

        if (!empty($data['id'])) {
            $formatted['id'] = $data['id'];
        }

        $formatted += [
            'identifier' => $this->formatIdentifier($data),
            'status' => $data['status'] ?? 'arrived',
            'class' => $this->formatClass($data),
            'subject' => $this->formatSubject($data),
            'participant' => $this->formatParticipant($data),
            'period' => $this->formatPeriodStart($data),
            'location' => $this->formatLocation($data)
        ];

        if (($data['status'] ?? null) === 'finished') {
            $formatted['diagnosis'] = $this->formatDiagnosis($data);
        }

        $formatted += [
            'statusHistory'   => $this->formatStatusHistory($data),
            'serviceProvider' => $this->formatServiceProvider($data),
        ];

        return $formatted;
    }

    private function formatClass(array $data): array
    {
        $classCode = $data['class_code'] ?? 'AMB';

        $classMap = [
            'AMB' => 'ambulatory',
            'IMP' => 'inpatient encounter',
            'EMER' => 'emergency',
            'HH' => 'home health',
            'VR' => 'virtual',
            'OBSENC' => 'observation encounter',
            'PRENC' => 'pre-admission',
            'SS' => 'short stay',
            'ACUTE' => 'acute care',
            'NONAC' => 'non-acute care',
        ];

        $classDisplay = $classMap[$classCode] ?? 'unknown';

        return [
            'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
            'code'    => $classCode,
            'display' => $classDisplay,
        ];
    }

    private function formatSubject($data): array
    {
        if (!empty($data['patient_id']) && !empty($data['patient_name'])) {
            return [
                "reference" => "Patient/" . $data['patient_id'],
                "display"   => $data['patient_name']
            ];
        }

        return [];
    }

    private function formatParticipant(array $data): array
    {
        if (!isset($data['practitioner_id'])) return [];

        return [[
            'type' => [[
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                    'code' => 'ATND',
                    'display' => 'attender'
                ]]
            ]],
            'individual' => [
                'reference' => 'Practitioner/' . $data['practitioner_id'],
                'display' => $data['practitioner_name'] ?? null,
            ]
        ]];
    }

    private function formatPeriodStart(array $data): array
    {
        return [
            'start' => $data['start']
        ];
    }

    private function formatLocation(array $data): array
    {
        if (empty($data['location_id'])) {
            return [];
        }

        $location = [
            'location' => [
                'reference' => 'Location/' . $data['location_id'],
                'display'   => $data['location_name'] ?? null,
            ],
        ];

        // period start (opsional)
        if (!empty($data['start'])) {
            $location['period'] = [
                'start' => $data['start'],
            ];
        }

        // extension (opsional)
        if (!empty($data['service_class_code']) || !empty($data['upgrade_class_code'])) {
            $extensions = [];

            if (!empty($data['service_class_code'])) {
                $extensions[] = [
                    'url' => 'value',
                    'valueCodeableConcept' => [
                        'coding' => [[
                            'system'  => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient',
                            'code'    => $data['service_class_code'],
                            'display' => $data['service_class_display'] ?? $data['service_class_code'],
                        ]],
                    ],
                ];
            }

            if (!empty($data['upgrade_class_code'])) {
                $extensions[] = [
                    'url' => 'upgradeClassIndicator',
                    'valueCodeableConcept' => [
                        'coding' => [[
                            'system'  => 'http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass',
                            'code'    => $data['upgrade_class_code'],
                            'display' => $data['upgrade_class_display'] ?? $data['upgrade_class_code'],
                        ]],
                    ],
                ];
            }

            $location['extension'] = [[
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass',
                'extension' => $extensions,
            ]];
        }

        return [$location];
    }


    private function formatStatusHistory(array $data): array
    {
        $history = [];

        // Kalau user sudah kasih status_history array, langsung proses
        if (!empty($data['status_history']) && is_array($data['status_history'])) {
            foreach ($data['status_history'] as $item) {
                $entry = [
                    'status' => $item['status'] ?? 'arrived',
                    'period' => [
                        'start' => $item['start'] ?? null,
                    ],
                ];

                if (!empty($item['end'])) {
                    $entry['period']['end'] = $item['end'];
                }

                $history[] = $entry;
            }
        } else {
            $history[] = [
                'status' => $data['status'] ?? 'arrived',
                'period' => [
                    'start' => $data['start'] ?? now()->toIso8601String(),
                ],
            ];
        }

        return $history;
    }


    private function formatServiceProvider(array $data): array
    {
        return [
            'reference' => 'Organization/' . $data['organization_id']
        ];
    }

    private function formatIdentifier(array $data): array
    {
        return [[
            'system' => 'http://sys-ids.kemkes.go.id/encounter/' . $data['organization_id'],
            'value' => $data['identifier']
        ]];
    }

    private function formatDiagnosis(array $data): array
    {
        if (empty($data['diagnosis'])) {
            return [];
        }

        $result = [];

        foreach ($data['diagnosis'] as $item) {
            $diagnosis = [
                "condition" => [
                    "reference" => "Condition/" . $item["condition_id"],
                ]
            ];

            if (!empty($item["condition_display"])) {
                $diagnosis["condition"]["display"] = $item["condition_display"];
            }

            // coding
            if (!empty($item["use_code"])) {
                $diagnosis["use"] = [
                    "coding" => [[
                        "system"  => "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                        "code"    => $item["use_code"],
                        "display" => $item["use_display"] ?? null,
                    ]]
                ];
            }

            // rank optional
            if (!empty($item["rank"])) {
                $diagnosis["rank"] = (int) $item["rank"];
            }

            $result[] = $diagnosis;
        }

        return $result;
    }
}
