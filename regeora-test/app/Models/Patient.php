<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['first_name', 'second_name', 'birthdate'];

    public function getAgeAttribute(): int|string
    {
        [$value, $type] = $this->calculateAgeAndType();
        return $value;
    }

    /**
     * @throws \Exception
     */
    public function getAgeTypeAttribute(): string
    {
        [$value, $type] = $this->calculateAgeAndType();
        return $type;
    }

    private function calculateAgeAndType(): array
    {
        $interval = (new DateTime($this->birthdate))->diff(new DateTime());

        return $interval->y >= 1
            ? [$interval->y, 'год']
            : ($interval->m >= 1
                ? [$interval->m, 'месяц']
                : [$interval->days, 'день']);
    }
}
