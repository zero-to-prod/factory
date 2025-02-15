<?php

namespace Tests\Unit\CallableState;

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

    public function setLastName(): self
    {
        return $this->state(function ($context) {
            return [User::last_name => $context[User::first_name]];
        });
    }

    public static function factory(array $context = []): self
    {
        return new self($context);
    }
}