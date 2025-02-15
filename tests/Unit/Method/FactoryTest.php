<?php

namespace Tests\Unit\Method;

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
        $User = User::factory()
            ->setFirstName('Jim')
            ->make();

        self::assertEquals('Jim', $User[User::first_name]);
        self::assertEquals('N/A', $User[User::last_name]);
    }

    /**
     * @test
     *
     * @see Factory
     */
    public function nested_element(): void
    {
        $User = User::factory()
            ->setAddress('bogus')
            ->make();

        self::assertEquals('bogus', $User[User::address]['street']);
    }

    /**
     * @test
     *
     * @see Factory
     */
    public function nested_array(): void
    {
        $User = User::factory()
            ->setShippingAddress(['bogus' => 'Bogus'])
            ->make();

        self::assertEquals(['bogus' => 'Bogus'], $User[User::shipping_address]);
    }
}