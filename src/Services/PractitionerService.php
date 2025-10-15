<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\SatuSehatService;
use InvalidArgumentException;

class PractitionerService
{
    /**
     * @var SatuSehatService
     */
    protected SatuSehatService $satuSehatService;

    /**
     * @var string
     */
    protected string $endpoint = '/fhir-r4/v1/Practitioner';

    /**
     * Constructor
     *
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(SatuSehatService $satuSehatService)
    {
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Search Practitioner by NIK
     *
     * @param string $nik
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getByNik(string $nik)
    {
        if (empty($nik)) {
            throw new InvalidArgumentException('NIK is required for Practitioner search.');
        }

        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }


    /**
     * Search Practitioner by NIK and Name
     *
     * @param string $nik
     * @param string $name
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getByNikAndName(string $nik, string $name)
    {
        if (empty($nik) || empty($name)) {
            throw new InvalidArgumentException('Both NIK and name are required for Practitioner search.');
        }

        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
            'name' => $name,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Search Practitioner by Name, Gender, and Birthdate
     *
     * @param string $name
     * @param string $gender
     * @param string $birthdate
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getByNameGenderBirthdate(string $name, string $gender, string $birthdate)
    {
        if (empty($name) || empty($gender) || empty($birthdate)) {
            throw new InvalidArgumentException('Name, gender, and birthdate are required for Practitioner search.');
        }

        $params = [
            'name' => $name,
            'gender' => $gender,
            'birthdate' => $birthdate,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }
}
