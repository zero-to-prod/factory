<?php

namespace Tests\Unit\Merge;

use Tests\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @test
     *
     * @see Factory
     */
    public function merge(): void
    {
        $User = UserFactory::factory()
            ->set(User::first_name, 'John')
            ->set([User::last_name => 'Doe'])
            ->set(function (array $context) {
                return [User::surname => $context[User::last_name]];
            })
            ->set('address.postal_code', '46789')
            ->merge([User::last_name => 'merged'])
            ->make();

        self::assertEquals('John', $User[User::first_name]);
        self::assertEquals('merged', $User[User::last_name]);
        self::assertEquals('Doe', $User[User::surname]);
        self::assertEquals('46789', $User[User::address]['postal_code']);
    }
}