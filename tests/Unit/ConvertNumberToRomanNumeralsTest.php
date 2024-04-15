<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Actions\ConvertNumberToRomanNumerals;

class ConvertNumberToRomanNumeralsTest extends TestCase
{
    protected $action;
    protected $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->action = new ConvertNumberToRomanNumerals();
    }

    public function test_that_roman_numerals_of_1(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(1), 'I');
    }

    public function test_that_roman_numerals_of_10(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(10), 'X');
    }

    public function test_that_roman_numerals_of_50(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(50), 'L');
    }

    public function test_that_roman_numerals_of_400(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(400), 'CD');
    }

    public function test_that_roman_numerals_of_900(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(900), 'CM');
    }

    public function test_that_roman_numerals_of_1000(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(1000), 'M');
    }

    public function test_that_roman_numerals_of_1001(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(1001), 'I_I');
    }

    public function test_that_roman_numerals_of_99999(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(99999), 'XCIX_CMXCIX');
    }

    public function test_that_roman_numerals_of_100000(): void
    {
        $this->assertEquals($this->action->calculateRomanNumeralEquivalent(100000), 'C_');
    }
}
