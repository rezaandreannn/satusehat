<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class OrganizationFHIR
{
    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'Organization',
            'active' => true
        ];

        if (!empty($data['id'])) {
            $formatted['id'] = $data['id'];
        }

        $formatted += [
            'identifier' => $this->identifier($data),
            'type' => $this->formatType($data),
            'name' => $data['name'],
            'telecom' => $this->formatTelecom($data),
            'address' => [$this->formatAddress($data)],
            'partOf' => $this->partOf($data)
        ];

        return $formatted;
    }

    private function identifier($data): array
    {
        return [
            "use" => "official",
            "system" => "http://sys-ids.kemkes.go.id/organization/" . $data['organization_id'],
            "value" =>  $data['identifier_value']
        ];
    }

    private function formatType($data): array
    {
        $code    = $data['type_code'] ?? 'dept';
        $display = $data['type_display'] ?? 'Hospital Department';

        return [[
            'coding' => [[
                'system'  => 'http://terminology.hl7.org/CodeSystem/organization-type',
                'code'    => $code,
                'display' => $display
            ]]
        ]];
    }

    private function formatTelecom($data): array
    {
        // return array_filter([
        //     [
        //         'system' => 'phone',
        //         'value' => $data['phone'] ?? null,
        //         'use' => 'work'
        //     ],
        //     [
        //         'system' => 'email',
        //         'value' => $data['email'] ?? null,
        //         'use' => 'work'
        //     ],
        //     [
        //         'system' => 'url',
        //         'value' => $data['url'] ?? null,
        //         'use' => 'work'
        //     ]
        // ], fn($t) => $t['value'] !== null);
        $telecoms = [
            ['system' => 'phone', 'value' => $data['phone'] ?? null, 'use' => 'work'],
            ['system' => 'email', 'value' => $data['email'] ?? null, 'use' => 'work'],
            ['system' => 'url', 'value' => $data['url'] ?? null, 'use' => 'work'],
        ];

        // remove empty values and reindex numeric keys
        return array_values(array_filter($telecoms, fn($t) => !empty($t['value'])));
    }

    private function formatAddress($data): ?array
    {
        // return [
        //     'use' => 'work',
        //     "type" => "both",
        //     'line' => [$data['address'] ?? '-'],
        //     'city' => $data['city'] ?? '',
        //     'postalCode' => $data['postal_code'] ?? '',
        //     'country' => 'ID',
        //     'extension' => [[
        //         'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
        //         'extension' => array_filter([
        //             ['url' => 'province', 'valueCode' => $data['province_code'] ?? null],
        //             ['url' => 'city', 'valueCode' => $data['city_code'] ?? null],
        //             ['url' => 'district', 'valueCode' => $data['district_code'] ?? null],
        //             ['url' => 'village', 'valueCode' => $data['village_code'] ?? null],
        //         ], fn($item) => $item['valueCode'] !== null)
        //     ]]
        // ];
        $address = [
            'use' => 'work',
            'type' => 'both',
            'line' => [$data['address'] ?? '-'],
            'country' => 'ID',
        ];

        if (!empty($data['city'])) {
            $address['city'] = $data['city'];
        }

        if (!empty($data['postal_code'])) {
            $address['postalCode'] = $data['postal_code'];
        }

        $extensions = array_filter([
            ['url' => 'province', 'valueCode' => $data['province_code'] ?? null],
            ['url' => 'city', 'valueCode' => $data['city_code'] ?? null],
            ['url' => 'district', 'valueCode' => $data['district_code'] ?? null],
            ['url' => 'village', 'valueCode' => $data['village_code'] ?? null],
        ], fn($item) => !empty($item['valueCode']));

        if (!empty($extensions)) {
            $address['extension'] = [[
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                'extension' => array_values($extensions)
            ]];
        }

        return $address;
    }

    private function partOf($data): array
    {
        if (!empty($data['reference_organization'])) {
            return [
                "reference" => "Organization/" . $data['reference_organization']
            ];
        }

        return [];
    }
}
