<?php

namespace Rezaandreannn\SatuSehat\FHIR;

class LocationFHIR
{

    public function format(array $data)
    {
        $formatted = [
            'resourceType' => 'Location'
        ];

        if (!empty($data['id'])) {
            $formatted['id'] = $data['id'];
        }

        $formatted += [
            'identifier' => $this->identifier($data),
            'status' => $data['status'] ?? 'active',
            'name' => $data['name'],
            'description' => $data['description'],
            'mode' => $data['mode'] ?? 'instance',
            'telecom' => $this->formatTelecom($data),
            'physicalType' => $this->formatType($data),
            'managingOrganization' => $this->formatManagingOrganization($data)
        ];

        return $formatted;
    }

    private function identifier($data): array
    {
        return [
            "use" => "official",
            "system" => "http://sys-ids.kemkes.go.id/location/" . $data['organization_id'],
            "value" =>  $data['identifier_value']
        ];
    }

    private function formatTelecom($data): array
    {
        $telecoms = [
            ['system' => 'phone', 'value' => $data['phone'] ?? null, 'use' => 'work'],
            ['system' => 'email', 'value' => $data['email'] ?? null, 'use' => 'work'],
            ['system' => 'url', 'value' => $data['url'] ?? null, 'use' => 'work'],
        ];

        // remove empty values and reindex numeric keys
        return array_values(array_filter($telecoms, fn($t) => !empty($t['value'])));
    }

    private function formatType($data): array
    {
        $code    = $data['type_code'] ?? 'ro';
        $display = $data['type_display'] ?? 'room';

        return [
            'coding' => [[
                'system'  => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                'code'    => $code,
                'display' => $display
            ]]
        ];
    }

    private function formatManagingOrganization($data): array
    {
        return [
            'reference' => 'Organization/' . $data['managingOrganization']
        ];
    }
}
