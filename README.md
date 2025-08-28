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
- [Documentation Publishing](#documentation-publishing)
- [Usage](#usage)
  - [Basic Factory Pattern](#basic-factory-pattern)
  - [Model-Based Factory Pattern](#model-based-factory-pattern)
  - [Custom Factory Methods](#custom-factory-methods)
  - [Advanced Factory Features](#advanced-factory-features)
  - [Callable State (Closures)](#callable-state-closures)
  - [The `set()` Method](#the-set-method)
  - [The `merge()` Method](#the-merge-method)
  - [The `context()` Method](#the-context-method)
  - [Context Priority](#context-priority)
  - [The `make()` Method](#the-make-method)
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

This will add the package to your project's dependencies and create an autoloader entry for it.

## Documentation Publishing

You can publish this README to your local documentation directory.

This can be useful for providing documentation for AI agents.

This can be done using the included script:

```bash
# Publish to default location (./docs/zero-to-prod/factory)
vendor/bin/zero-to-prod-factory

# Publish to custom directory
vendor/bin/zero-to-prod-factory /path/to/your/docs
```

## Usage

### Basic Factory Pattern

The simplest factory implementation requires only the `Factory` trait and a `definition()` method:

```php
class UserFactory
{
    use \Zerotoprod\Factory\Factory;

    protected function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'N/A'
        ];
    }
    
    public static function factory(array $context = []): self
    {
        return new self($context);
    }
}

// Create an array with default values
$user = UserFactory::factory()->make();
echo $user['first_name']; // 'John'
echo $user['last_name'];  // 'N/A'

// Override specific values
$user = UserFactory::factory(['last_name' => 'Doe'])->make();
echo $user['first_name']; // 'John' 
echo $user['last_name'];  // 'Doe'
```

### Model-Based Factory Pattern

You can add a static `factory()` method to your model classes for more convenient usage:

```php
class User
{
    public const first_name = 'first_name';
    public $first_name;
    
    public static function factory(array $context = []): UserFactory
    {
        return new UserFactory($context);
    }
}

class UserFactory
{
    use \Zerotoprod\Factory\Factory;

    protected function definition(): array
    {
        return [
            User::first_name => 'John'
        ];
    }
}

// Call factory directly from the model
$user = User::factory()->make();
echo $user['first_name']; // 'John'

// Pass context to make() method
$user = User::factory()->make([User::first_name => 'Jane']);
echo $user['first_name']; // 'Jane'
```

### Custom Factory Methods

Create custom methods in your factory to provide a fluent, expressive interface for setting specific values. These methods use the `state()` method internally to modify the factory context.

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
                'street' => '123 Main St',
            ],
            'shipping_address' => [
                'street' => '123 Main St',
            ]
        ];
    }

    // Simple field setting with array syntax
    public function setFirstName(string $value): self
    {
        return $this->state(['first_name' => $value]);
    }

    // Nested field setting with dot notation
    public function setAddress(string $street): self
    {
        return $this->state('address.street', $street);
    }

    // Complete array replacement
    public function setShippingAddress(array $address): self
    {
        return $this->state('shipping_address', $address);
    }

    public static function factory(array $context = []): self
    {
        return new self($context);
    }
}

// Usage examples
$user = User::factory()
    ->setFirstName('Jim')          // Sets first_name to 'Jim'
    ->setAddress('bogus')          // Sets address.street to 'bogus'
    ->setShippingAddress(['city' => 'NYC']) // Replaces entire shipping_address
    ->make();

echo $user['first_name']; // 'Jim'
echo $user['address']['street']; // 'bogus'  
echo $user['shipping_address']['city']; // 'NYC'
```

### Advanced Factory Features

For more complex scenarios, you can add state manipulation with closures and other advanced patterns:

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
        /** Closure Syntax - access context values to create dynamic state */
        return $this->state(function ($context) {
            return ['last_name' => $context['first_name']];
        });
    }
    
    /** Static factory method for fluent instantiation */
    public static function factory(array $context = []): self
    {
        return new self($context);
    }
    
    /* Optionally implement for better static analysis */
    public function make(array $context = []): array
    {
        return $this->resolve($context);
    }
}

$User = UserFactory::factory([User::last_name => 'Doe'])
            ->setFirstName('Jane')
            ->make();
            
User::factory([User::last_name => 'Doe'])->make(); // Also works for this example

echo $User['first_name']; // 'Jane'
echo $User['last_name'];  // 'Doe'
```

### Callable State (Closures)

Use closures to create dynamic state based on the current context. This is powerful for creating values that depend on other values in the factory:

```php
class UserFactory
{
    use \Zerotoprod\Factory\Factory;

    protected function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'N/A'
        ];
    }

    public function setLastName(): self
    {
        return $this->state(function ($context) {
            // Set last_name to the same value as first_name
            return ['last_name' => $context['first_name']];
        });
    }
}

$User = UserFactory::factory()
    ->setLastName()
    ->make();

echo $User['first_name']; // 'John'
echo $User['last_name'];  // 'John' (copied from first_name)
```

The closure receives the current context as its parameter, allowing you to create dynamic relationships between fields.

### The `set()` Method

The `set()` method provides a flexible way to modify factory state without creating custom methods. It supports four different syntaxes to accommodate various use cases:

#### 1. Key-Value Syntax
Set a single field by passing the key and value as separate parameters:
```php
->set('first_name', 'John')
->set(User::first_name, 'John')  // Using constants
```

#### 2. Array Syntax  
Set multiple fields or use associative arrays:
```php
->set(['last_name' => 'Doe'])
->set(['first_name' => 'Jane', 'last_name' => 'Smith'])
```

#### 3. Closure Syntax
Use closures for dynamic values based on current context:
```php
->set(function ($context) {
    return ['surname' => $context['last_name']];
})
```

#### 4. Dot Notation Syntax
Access and modify nested array values:
```php
->set('address.postal_code', '46789')
->set('user.profile.settings.theme', 'dark')
```

#### Complete Example

Here's how all four syntaxes work together:

```php
$User = UserFactory::factory()
    ->set('first_name', 'John')                    // Key-value syntax
    ->set(['last_name' => 'Doe'])                  // Array syntax
    ->set(function ($context) {                    // Closure syntax
        return ['surname' => $context['last_name']];
    })
    ->set('address.postal_code', '46789')          // Dot syntax for nested values
    ->make();

echo $User['first_name'];           // John
echo $User['last_name'];            // Doe
echo $User['surname'];              // Doe
echo $User['address']['postal_code']; // 46789
```

### The `merge()` Method

The `merge()` method allows you to merge new values directly into the factory's current context. This is useful for bulk updates or overriding multiple values at once.

**Important:** `merge()` updates the context after `set()` methods have been evaluated, so closures will use the original values.

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
                'postal_code' => 'default'
            ]
        ];
    }
}

$User = UserFactory::factory()
    ->set('first_name', 'John')
    ->set(['last_name' => 'Doe'])
    ->set(function (array $context) {
        // This closure captures 'Doe' when set() is called
        return ['surname' => $context['last_name']]; 
    })
    ->set('address.postal_code', '46789')
    ->merge(['last_name' => 'merged'])  // Override after set() calls
    ->make();

echo $User['first_name']; // 'John'
echo $User['last_name'];  // 'merged' (from merge())
echo $User['surname'];    // 'Doe' (from closure, before merge())
echo $User['address']['postal_code']; // '46789'
```

Use `merge()` when you want to:
- Apply bulk changes to multiple fields
- Override previously set values
- Update context without creating new state methods

### The `context()` Method

Use the `context()` method to get the current context of the factory without creating the final result. This is useful for inspecting or debugging the factory state.

```php
class UserFactory
{
    use \Zerotoprod\Factory\Factory;

    protected function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'N/A',
        ];
    }
}

// Get context with initial values
$context = UserFactory::factory()->context();
echo $context['first_name']; // 'John'
echo $context['last_name'];  // 'N/A'

// Get context with overridden values
$context = UserFactory::factory(['last_name' => 'Doe'])->context();
echo $context['first_name']; // 'John'
echo $context['last_name'];  // 'Doe'
```

### Context Priority

Context can be provided at different stages, with later contexts taking priority:

```php
class UserFactory
{
    use \Zerotoprod\Factory\Factory;

    protected function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'N/A',
        ];
    }
}

// Context in make() overrides context in factory()
$User = UserFactory::factory(['last_name' => 'Bogus'])
    ->make(['last_name' => 'Doe']);

echo $User['first_name']; // 'John'
echo $User['last_name'];  // 'Doe' (from make(), not 'Bogus')
```

Priority order (highest to lowest):
1. Context passed to `make()` - **Final override at creation time**
2. Values from `merge()` method - **Direct context updates**
3. Context from `state()` methods - **Fluent method calls** 
4. Context passed to `factory()` - **Initial context setup**
5. Default values from `definition()` - **Base factory defaults**

**Note:** Closures in `state()` methods are evaluated when the method is called, so they use the context available at that time, before any subsequent `merge()` operations.

### The `make()` Method

The `make()` method is the final step that creates your data structure. It can accept optional context that will override any previously set values:

```php
$factory = UserFactory::factory(['first_name' => 'John'])
    ->set('last_name', 'Smith');

// Create with factory/state values
$user1 = $factory->make();
echo $user1['first_name']; // 'John'
echo $user1['last_name'];  // 'Smith'

// Override at make time
$user2 = $factory->make(['first_name' => 'Jane']);
echo $user2['first_name']; // 'Jane' (overridden)
echo $user2['last_name'];  // 'Smith' (preserved)
```

## Contributing

Contributions, issues, and feature requests are welcome!
Feel free to check the [issues](https://github.com/zero-to-prod/factory/issues) page if you want to contribute.

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a new Pull Request.
