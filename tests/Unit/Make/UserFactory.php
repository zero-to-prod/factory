<?php

namespace Tests\Unit\Make;

use Zerotoprod\Factory\Factory;

class UserFactory
{
    use Factory;

    protected function definition(): array
    {
        return [
            User::first_name => 'John'
        ];
    }
}