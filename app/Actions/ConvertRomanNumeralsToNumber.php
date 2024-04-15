<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;

class ConvertRomanNumeralsToNumber
{
    use AsAction;

    public function handle(Request $request)
    {
        // TODO add regex condition as an extra validation
        Validator::make($request->all(), [
            'roman_numerals' => ['required', 'string'],
        ])->validate();

        return $this->calculateNumberEquivalent($request->roman_numerals);

    }

    public function calculateNumberEquivalent($roman_numerals)
    {
        $rn_split = explode('_', $roman_numerals, 2);

        if (count($rn_split) == 2) {
            // An underscore (bar sign) exists
            $initial_thousand = str_replace('M', 'I', $rn_split[0]);
            $initial_thousand_number = $this->convertThroughIterativeComparison($initial_thousand) * 1000;

            $remainder = str_replace('_', '', $rn_split[1]);
            $remainder_number = $this->convertThroughIterativeComparison($remainder);

            return $initial_thousand_number + $remainder_number;
        } else {
            return $this->convertThroughIterativeComparison($roman_numerals);
        }
    }

    protected function convertThroughIterativeComparison($roman_numerals)
    {
        $number = 0;
        for ($i = 0; $i < strlen($roman_numerals); $i++) {
            $val_1 = $this->findValueFromMap($roman_numerals[$i]);
            if ($i + 1 < strlen($roman_numerals)) {
                $val_2 = $this->findValueFromMap($roman_numerals[$i + 1]);
                if ($val_1 >= $val_2) {
                    $number = $number + $val_1;
                } else {
                    $number = $number + $val_2 - $val_1;
                    $i++;
                }
            } else {
                $number = $number + $val_1;
                $i++;
            }
        }
        return $number;
    }

    protected function findValueFromMap($char)
    {
        foreach (Config::get('constants.NUMBER_RR_MAP') as $value => $equiv) {
            if ($equiv === $char) {
                return $value;
            }
        }
        return -1;
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }
}
