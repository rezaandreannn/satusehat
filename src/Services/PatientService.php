<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\PatientFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;
use InvalidArgumentException;

class PatientService
{
    /**
     * @var SatuSehatService
     */
    protected SatuSehatService $satuSehatService;

    /**
     * @var PatientFHIR
     */
    protected PatientFHIR $fhir;

    /**
     * @var string
     */
    protected string $endpoint = '/fhir-r4/v1/Patient';

    /**
     * Constructor
     *
     * @param PatientFHIR $fhir
     * @param SatuSehatService $satuSehatService
     */
    public function __construct(PatientFHIR $fhir, SatuSehatService $satuSehatService)
    {
        $this->fhir = $fhir;
        $this->satuSehatService = $satuSehatService;
    }

    /**
     * Get Patient by NIK
     *
     * @param string $nik
     * @return mixed
     */
    public function getByNik(string $nik)
    {
        if (empty($nik)) {
            throw new InvalidArgumentException('NIK is required for patient search.');
        }

        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Get Patient by NIK and Name
     *
     * @param string $nik
     * @param string $name
     * @return mixed
     */
    public function getByNikAndName(string $nik, string $name)
    {
        if (empty($nik) || empty($name)) {
            throw new InvalidArgumentException('NIK and name are required for patient search.');
        }

        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
            'name' => $name,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Get Patient by NIK, Name, and Birthdate
     *
     * @param string $nik
     * @param string $name
     * @param string $birthdate
     * @return mixed
     */
    public function getByNikNameBirthdate(string $nik, string $name, string $birthdate)
    {
        if (empty($nik) || empty($name) || empty($birthdate)) {
            throw new InvalidArgumentException('NIK, name, and birthdate are required.');
        }

        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
            'name' => $name,
            'birthdate' => $birthdate,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Get Patient by Name, Birthdate, and Gender
     *
     * @param string $name
     * @param string $birthdate
     * @param string $gender
     * @return mixed
     */
    public function getByNameBirthdateGender(string $name, string $birthdate, string $gender)
    {
        if (empty($name) || empty($birthdate) || empty($gender)) {
            throw new InvalidArgumentException('Name, birthdate, and gender are required.');
        }

        $params = [
            'name' => $name,
            'birthdate' => $birthdate,
            'gender' => $gender,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Get Baby Patient by Mother's NIK and Baby's Birthdate
     *
     * @param string $nikIbu
     * @param string $birthdateBayi
     * @return mixed
     */
    public function getBabyByMotherNik(string $nikIbu, string $birthdateBayi)
    {
        if (empty($nikIbu) || empty($birthdateBayi)) {
            throw new InvalidArgumentException('Mother NIK and baby birthdate are required.');
        }

        $params = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik-ibu|' . $nikIbu,
            'birthdate' => $birthdateBayi,
        ];

        return $this->satuSehatService->get($this->endpoint, $params);
    }

    /**
     * Get Patient by IHS (UUID)
     *
     * @param string $ihs
     * @return mixed
     */
    public function getByIhs(string $ihs)
    {
        if (empty($ihs)) {
            throw new InvalidArgumentException('IHS number is required.');
        }

        $endpoint = "{$this->endpoint}/{$ihs}";
        return $this->satuSehatService->get($endpoint);
    }

    /**
     * Create new Patient
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehatService->post($this->endpoint, $payload);
    }


    /**
     * Update existing Patient
     *
     * @param string $ihs
     * @param array $data
     * @return mixed
     */
    public function update(string $ihs, array $data)
    {
        if (empty($ihs)) {
            throw new InvalidArgumentException('IHS number is required for update.');
        }

        $endpoint = "{$this->endpoint}/{$ihs}";
        $payload = $this->fhir->format($data);

        return $this->satuSehatService->put($endpoint, $payload);
    }
}
