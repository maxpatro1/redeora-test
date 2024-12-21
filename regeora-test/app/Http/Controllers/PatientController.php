<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Jobs\ProcessPatientJob;
use App\Models\Patient;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class PatientController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $patients = Cache::get('patients_list');
        if (!$patients) {
            $patients = Patient::all();
            Cache::put('patients_list', PatientResource::collection($patients), 300);
        }
        return $patients;
    }

    public function store(PatientRequest $request): PatientResource
    {
        $validated = $request->validated();
        $patient = Patient::create($validated);
        $this->addPatientToCache($patient);
        ProcessPatientJob::dispatch($patient);
        return new PatientResource($patient);
    }

    private function addPatientToCache(Patient $patient)
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
