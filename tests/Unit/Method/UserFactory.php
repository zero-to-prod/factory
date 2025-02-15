<?php

namespace Tests\Unit\Method;

use Zerotoprod\Factory\Factory;

class UserFactory
{
    use Factory;

    protected function definition(): array
    {
        return [
            User::first_name => 'John',
            User::last_name => 'N/A',
            User::address => [
                'street' => '123 Main St',
            ],
            User::shipping_address => [
                'street' => '123 Main St',
            ]
        ];
    }

    public function setAddress(string $value): self
    {
        return $this->state('address.street', $value);
    }

    public function setShippingAddress(array $value): self
    {
        return $this->state('shipping_address', $value);
    }

    public function setFirstName(string $value): self
    {
        return $this->state([User::first_name => $value]);
    }

}