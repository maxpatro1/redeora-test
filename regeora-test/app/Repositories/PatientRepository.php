<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Cache;

class PatientRepository
{
    public function addPatientToCache(Patient $patient)
    {
        $patients = Cache::get('patients_list', collect());
        if ($patients->isEmpty()) {
            return $patients;
        }
        $patients->push($patient);
        Cache::put('patients_list', $patients, 300);
        return $patients;
    }
}
