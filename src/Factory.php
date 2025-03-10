<?php

namespace Zerotoprod\Factory;

use Zerotoprod\Arr\Arr;

/**
 * A Generic Factory Pattern for Creating Arrays
 *
 * ```
 *  class UserFactory
 *  {
 *      use Factory;
 *
 *      public function definition(): array
 *      {
 *          return [
 *              'first_name' => 'John'
 *          ];
 *      }
 *
 *      public function setLastName(): self
 *      {
 *          return $this->state(['last_name' => 'Doe']);
 *      }
 *
 *      public function make(): array
 *      {
 *          return $this->resolve();
 *      }
 *
 *      public static function factory(array $context = []): self
 *      {
 *          return new self($context);
 *      }
 *  }
 *
 *  $User = UserFactory::factory(['first_name' => 'Bill'])
 *              ->setLastName('Smith')
 *              ->make();
 *
 * echo $User['last_name']; // 'Smith'
 * ```
 *
 * @link https://github.com/zero-to-prod/factory
 */
trait Factory
{
    /**
     * Stores the values for the class.
     *
     * @var array
     *
     * @link https://github.com/zero-to-prod/factory
     */
    private $context;

    /**
     * Initial values.
     *
     * @link https://github.com/zero-to-prod/factory
     */
    public function __construct(array $context = [])
    {
        $this->context = array_merge($this->definition(), $context);
    }

    /**
     * Define the class's default values.
     *
     * @return array<string, mixed>
     *
     * @link https://github.com/zero-to-prod/factory
     */
    private function definition(): array
    {
        return [];
    }

    /**
     * Merge the context with the new values.
     *
     * @link https://github.com/zero-to-prod/factory
     */
    public function merge(array $definition = []): self
    {
        $this->context = array_merge($this->context, $definition);

        return $this;
    }

    /**
     * Return the current context.
     *
     * @return array<string, mixed>
     *
     * @link https://github.com/zero-to-prod/factory
     */
    public function context(): array
    {
        return $this->context;
    }

    /**
     * Instantiate a new factory instance.
     *
     * @param  array  $context
     *
     * @return self
     *
     * @link https://github.com/zero-to-prod/factory
     */
    public static function factory(array $context = []): self
    {
        return new static($context);
    }

    /**
     * Merge the definition with the context.
     *
     * @link https://github.com/zero-to-prod/factory
     * @return array<string, mixed>
     */
    public function resolve()
    {
        return array_merge($this->definition(), $this->context);
    }

    /**
     * Overload this to return what you want.
     *
     * @link https://github.com/zero-to-prod/factory
     */
    public function make()
    {
        return $this->resolve();
    }

    /**
     * Mutate the context before instantiating the class.
     *
     * ```
     *   public function setValue($value): self
     *   {
     *        return $this->state('value.nested', $value);
     *   }
     *
     *  public function setValue($value): self
     *  {
     *       return $this->state(['value' => $value]);
     *  }
     *
     *  public function setValueWithClosure($value): self
     *  {
     *      return $this->state(function ($context) use ($value) {
     *          return ['value' => $value];
     *      });
     *  }
     * ```
     *
     * @param  callable|array  $state
     * @param  null            $value
     *
     * @return self
     *
     * @link https://github.com/zero-to-prod/factory
     */
    private function state($state, $value = null): self
    {
        $this->context = Arr::set($this->context, $state, $value);

        return $this;
    }

    /**
     * Mutate the context before instantiating the class.
     *
     * Alias for state().
     *
     * ```
     *   public function setValue($value): self
     *   {
     *        return $this->set('value.nested', $value);
     *   }
     *
     *  public function setValue($value): self
     *  {
     *       return $this->set(['value' => $value]);
     *  }
     *
     *  public function setValueWithClosure($value): self
     *  {
     *      return $this->set(function ($context) use ($value) {
     *          return ['value' => $value];
     *      });
     *  }
     * ```
     *
     * @param  callable|array  $state
     * @param  null            $value
     *
     * @return self
     *
     * @link https://github.com/zero-to-prod/factory
     */
    public function set($state, $value = null): self
    {
        $this->state($state, $value);

        return $this;
    }
}