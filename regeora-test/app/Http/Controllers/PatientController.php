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
    private \PatientRepository $repository;

    public function __construct(\PatientRepository $repository)
    {
        $this->repository = $repository;
    }

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
        $this->repository->addPatientToCache($patient);
        ProcessPatientJob::dispatch($patient);
        return new PatientResource($patient);
    }
}
