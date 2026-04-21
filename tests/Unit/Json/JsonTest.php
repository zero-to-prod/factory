<?php

namespace Tests\Unit\Context;

use Tests\TestCase;

class JsonTest extends TestCase
{
    /**
     * @test
     *
     * @see Factory
     */
    public function context(): void
    {
        $User = User::factory([User::last_name => 'Doe'])->json();

        self::assertEquals('{"first_name":"John","last_name":"Doe"}', $User);
    }
}