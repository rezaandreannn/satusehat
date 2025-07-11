<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class PatientFHIR
{
    public function format(array $data, bool $isUpdate = false): array
    {
        $formatted = [
            'resourceType' => 'Patient',
        ];

        // Tambahkan 'id' hanya jika update dan id tersedia
        if ($isUpdate && isset($data['id'])) {
            $formatted['id'] = $data['id'];
        }

        // Tambahkan key lainnya
        $formatted += [
            'meta' => [
                'profile' => [
                    'https://fhir.kemkes.go.id/r4/StructureDefinition/Patient'
                ]
            ],
            'identifier' => $this->formatIdentifiers($data),
            'active' => true,
            'name' => [[
                'use' => 'official',
                'text' => $data['name'] ?? '-'
            ]],
            'telecom' => $this->formatTelecom($data),
            'gender' => $data['gender'] ?? 'unknown',
            'birthDate' => $data['birthDate'] ?? null,
            'deceasedBoolean' => false,
            'address' => [$this->formatAddress($data)],
            'maritalStatus' => $this->formatMaritalStatus($data),
            'multipleBirthInteger' => 0,
            'contact' => $this->formatContact($data),
            'communication' => $this->formatCommunication($data),
            'extension' => $this->formatExtensions($data),
        ];

        return $formatted;
    }

    private function formatIdentifiers($data): array
    {
        $identifiers = [];

        if (isset($data['nik'])) {
            $identifiers[] = [
                'use' => 'official',
                'system' => 'https://fhir.kemkes.go.id/id/nik',
                'value' => $data['nik']
            ];
        }

        if (isset($data['paspor'])) {
            $identifiers[] = [
                'use' => 'official',
                'system' => 'https://fhir.kemkes.go.id/id/paspor',
                'value' => $data['paspor']
            ];
        }

        if (isset($data['kk'])) {
            $identifiers[] = [
                'use' => 'official',
                'system' => 'https://fhir.kemkes.go.id/id/kk',
                'value' => $data['kk']
            ];
        }

        return $identifiers;
    }

    private function formatTelecom($data): array
    {
        return array_filter([
            [
                'system' => 'phone',
                'value' => $data['mobile'] ?? null,
                'use' => 'mobile'
            ],
            [
                'system' => 'phone',
                'value' => $data['phone'] ?? null,
                'use' => 'home'
            ],
            [
                'system' => 'email',
                'value' => $data['email'] ?? null,
                'use' => 'home'
            ]
        ], fn($t) => $t['value'] !== null);
    }

    private function formatAddress($data): array
    {
        return [
            'use' => 'home',
            'line' => [$data['alamat'] ?? '-'],
            'city' => $data['city'] ?? '',
            'postalCode' => $data['postalCode'] ?? '',
            'country' => 'ID',
            'extension' => [[
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                'extension' => array_filter([
                    ['url' => 'province', 'valueCode' => $data['provinceCode'] ?? null],
                    ['url' => 'city', 'valueCode' => $data['cityCode'] ?? null],
                    ['url' => 'district', 'valueCode' => $data['districtCode'] ?? null],
                    ['url' => 'village', 'valueCode' => $data['villageCode'] ?? null],
                    ['url' => 'rt', 'valueCode' => $data['rt'] ?? null],
                    ['url' => 'rw', 'valueCode' => $data['rw'] ?? null],
                ], fn($item) => $item['valueCode'] !== null)
            ]]
        ];
    }

    private function formatMaritalStatus($data): ?array
    {
        if (!isset($data['maritalStatus'])) return null;

        return [
            'coding' => [[
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus',
                'code' => $data['maritalStatus'],
                'display' => $data['maritalStatusText'] ?? 'Unknown'
            ]],
            'text' => $data['maritalStatusText'] ?? 'Unknown'
        ];
    }

    private function formatContact($data): array
    {
        if (!isset($data['contactName'])) return [];

        return [[
            'relationship' => [[
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                    'code' => $data['contactRelationship'] ?? 'C'
                ]]
            ]],
            'name' => [
                'use' => 'official',
                'text' => $data['contactName']
            ],
            'telecom' => [[
                'system' => 'phone',
                'value' => $data['contactPhone'] ?? '',
                'use' => 'mobile'
            ]]
        ]];
    }

    private function formatCommunication($data): array
    {
        return [[
            'language' => [
                'coding' => [[
                    'system' => 'urn:ietf:bcp:47',
                    'code' => 'id-ID',
                    'display' => 'Indonesian'
                ]],
                'text' => 'Indonesian'
            ],
            'preferred' => true
        ]];
    }

    private function formatExtensions($data): array
    {
        $extensions = [];

        if (isset($data['birthPlaceCity'])) {
            $extensions[] = [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace',
                'valueAddress' => [
                    'city' => $data['birthPlaceCity'],
                    'country' => 'ID'
                ]
            ];
        }

        if (isset($data['citizenship'])) {
            $extensions[] = [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus',
                'valueCode' => $data['citizenship']
            ];
        }

        return $extensions;
    }
}
