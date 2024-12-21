<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => "{$this->first_name} {$this->second_name}",
            'birthdate' => (new DateTime($this->birthdate))->format('d.m.Y'),
            'age' => "{$this->age} {$this->age_type}",
        ];
    }
}
