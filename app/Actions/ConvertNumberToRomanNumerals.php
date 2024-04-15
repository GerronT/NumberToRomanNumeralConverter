<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConvertNumberToRomanNumerals
{
    use AsAction;

    private $MAP = [
        1000 => 'M',
        900 => 'CM',
        500 => 'D',
        400 => 'CD',
        100 => 'C',
        90 => 'XC',
        50 => 'L',
        40 => 'XL',
        10 => 'X',
        9 => 'IX',
        5 => 'V',
        4 => 'IV',
        1 => 'I'
    ];

    public function handle(Request $request)
    {
        Validator::make($request->all(), [
            'number' => ['required', 'integer', 'min:1', 'max:100000'],
        ])->validate();

        return $this->calculateRomanNumeralEquivalent($request->number);
    }

    public function calculateRomanNumeralEquivalent($number)
    {
        if ($number <= 1000) {
            // For numbers less than or equal to 1000, apply algorithm normally
            return $this->convertRecursively($number);
        } else {
            // If number is greater than 1000, separate initial thousand from remainder. 
            $initial_thousand = intval($number / 1000);
            $rn = $this->convertRecursively($initial_thousand);

            $remainder = $number % 1000;
            $r_rn = $this->convertRecursively($remainder);

            return $rn . '_' . $r_rn;
        }
    }

    protected function convertRecursively($number)
    {
        foreach ($this->MAP as $value => $equiv) {
            if ($number >= $value) {
                return $equiv  . $this->convertRecursively($number - $value);
            }
        }
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
