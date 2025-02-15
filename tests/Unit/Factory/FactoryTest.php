<?php

namespace Tests\Unit\Factory;

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
        $User = UserFactory::factory()->make();

        self::assertEquals('John', $User[User::first_name]);
        self::assertEquals('N/A', $User[User::last_name]);
    }
}