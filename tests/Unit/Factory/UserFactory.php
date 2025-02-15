<?php

namespace Tests\Unit\Factory;

use Zerotoprod\Factory\Factory;

class UserFactory
{
    use Factory;

    protected function definition(): array
    {
        return [
            User::first_name => 'John',
            User::last_name => 'N/A'
        ];
    }
}