<?php

namespace Tests\Unit\Context;

use Tests\TestCase;

class ContextTest extends TestCase
{
    /**
     * @test
     *
     * @see Factory
     */
    public function from(): void
    {
        $User = User::factory([User::last_name => 'Doe'])->make();

        self::assertEquals('John', $User[User::first_name]);
        self::assertEquals('Doe', $User[User::last_name]);
    }

    /**
     * @test
     *
     * @see Factory
     */
    public function context(): void
    {
        $User = User::factory([User::last_name => 'Doe'])->context();

        self::assertEquals('John', $User[User::first_name]);
        self::assertEquals('Doe', $User[User::last_name]);
    }
}