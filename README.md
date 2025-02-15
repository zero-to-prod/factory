# Zerotoprod\Factory

![](art/logo.png)

[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/factory)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/factory/test.yml?label=test)](https://github.com/zero-to-prod/factory/actions)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/factory/backwards_compatibility.yml?label=backwards_compatibility)](https://github.com/zero-to-prod/factory/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zero-to-prod/factory?color=blue)](https://packagist.org/packages/zero-to-prod/factory/stats)
[![php](https://img.shields.io/packagist/php-v/zero-to-prod/factory.svg?color=purple)](https://packagist.org/packages/zero-to-prod/factory/stats)
[![Packagist Version](https://img.shields.io/packagist/v/zero-to-prod/factory?color=f28d1a)](https://packagist.org/packages/zero-to-prod/factory)
[![License](https://img.shields.io/packagist/l/zero-to-prod/factory?color=pink)](https://github.com/zero-to-prod/factory/blob/main/LICENSE.md)
[![wakatime](https://wakatime.com/badge/github/zero-to-prod/factory.svg)](https://wakatime.com/badge/github/zero-to-prod/factory)
[![Hits-of-Code](https://hitsofcode.com/github/zero-to-prod/factory?branch=main)](https://hitsofcode.com/github/zero-to-prod/factory/view?branch=main)

## Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
  - [The `set()` Method](#the-set-method)
  - [The `merge()` Method](#the-merge-method)
  - [The `context()` Method](#the-context-method)
- [Local Development](./LOCAL_DEVELOPMENT.md)
- [Contributing](#contributing)

## Introduction

A Generic Factory Pattern for Creating Arrays.

## Requirements

- PHP 7.1 or higher.

## Installation

Install `Zerotoprod\Factory` via [Composer](https://getcomposer.org/):

```bash
composer require zero-to-prod/factory
```

This will add the package to your project’s dependencies and create an autoloader entry for it.

## Usage

Define a factory in this way:

```php

class UserFactory
{
    use \Zerotoprod\Factory\Factory;

    protected function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'N/A',
            'address' => [
                'street' => 'Memory Lane'
            ]
        ];
    }
    
    public function setStreet(string $value): self
    {
        /** Dot Syntax */
        return $this->state('address.street', $value);
    }
    
    public function setFirstName(string $value): self
    {
        /** Array Syntax */
        return $this->state(['first_name' => $value]);
    }
    
    public function setLastName(): self
    {
        /** Closure Syntax */
        return $this->state(function ($context) {
            return ['first_name' => $context['last_name']];
        });
    }
    
    /* Optionally implement for better static analysis */
    public function make(): array
    {
        return $this->resolve();
    }
}

$User = UserFactory::factory([User::last_name => 'Doe'])
            ->setFirstName('Jane')
            ->make();
            
User::factory([User::last_name => 'Doe'])->make(); // Also works for this example

echo $User['first_name']; // 'Jane'
echo $User['last_name'];  // 'Doe'
```

### The `set()` Method

You can use the `set()` helper method to fluently modify the state of your model in a convenient way.

This is a great way to modify a model without having to implement a method in the factory.

```php
$User = User::factory()
            ->set('first_name', 'John')
            ->set(['last_name' => 'Doe'])
            ->set(function ($context) {
                return ['surname' => $context['last_name']];
            })
            ->set('address.postal_code', '46789') // dot syntax for nested values 
            ->make();

echo $User->first_name;             // John
echo $User->last_name;              // Doe
echo $User->surname;                // Doe
echo $User->address->postal_code;   // 46789
```

### The `merge()` Method

Sometimes it is useful to merge new values into the current context of the factory.

Use the `merge()` method to merge any new values and update the factory context.

```php
class UserFactory
{
    use \Zerotoprod\DataModelFactory\Factory;

    private function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];
    }
}

$User = UserFactory::factory()
    ->merge(['first_name' => 'Jane'])
    ->make();

echo $User->first_name; // 'Jane'
echo $User->last_name;  // 'Doe'
```

### The `context()` Method

Use the `context()` method to get the context of the factory.

```php
class UserFactory
{
    use \Zerotoprod\DataModelFactory\Factory;

    private function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];
    }
}

$User = UserFactory::factory()->context();

echo $User['first_name']; // 'John'
echo $User['last_name'];  // 'Doe'
```

## Contributing

Contributions, issues, and feature requests are welcome!
Feel free to check the [issues](https://github.com/zero-to-prod/factory/issues) page if you want to contribute.

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a new Pull Request.
