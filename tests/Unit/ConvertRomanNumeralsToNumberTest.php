<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Actions\ConvertRomanNumeralsToNumber;

class ConvertRomanNumeralsToNumberTest extends TestCase
{
    protected $action;
    protected $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->action = new ConvertRomanNumeralsToNumber();
    }

    public function test_the_numerical_of_I(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('I'), 1);
    }

    public function test_the_numerical_of_X(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('X'), 10);
    }

    public function test_the_numerical_of_L(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('L'), 50);
    }

    public function test_the_numerical_of_CD(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('CD'), 400);
    }

    public function test_the_numerical_of_CM(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('CM'), 900);
    }

    public function test_the_numerical_of_M(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('M'), 1000);
    }

    public function test_the_numerical_of_MI(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('MI'), 1001);
    }

    // alt for above - same result
    public function test_the_numerical_of_I_I(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('I_I'), 1001);
    }

    public function test_the_numerical_of_MMM(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('MMM'), 3000);
    }

    // alt for above - same result
    public function test_the_numerical_of_III_(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('III_'), 3000);
    }

    public function test_the_numerical_of_XCIX_CMXCIX(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('XCIX_CMXCIX'), 99999);
    }

    // alt for above - same result
    public function test_the_numerical_of_XCMX_CMXCIX(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('XCMX_CMXCIX'), 99999);
    }

    public function test_the_numerical_of_C_(): void
    {
        $this->assertEquals($this->action->calculateNumberEquivalent('C_'), 100000);
    }
}
