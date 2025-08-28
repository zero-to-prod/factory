<?php

namespace Tests\Unit\Make;

use Tests\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @test
     *
     * @see Factory
     */
    public function from(): void
    {
        $User = User::factory()->make();

        self::assertEquals('John', $User[User::first_name]);
    }

    public function make(): void
    {
        $User = User::factory()->make([User::first_name => 'Jane']);

        self::assertEquals('Jane', $User[User::first_name]);
    }
}