<?php

class Fiber
{
    /**
     * @var array Injectors
     */
    protected $injectors;

    /**
     * @param array $injectors
     */
    public function __construct(array $injectors = array())
    {
        $this->injectors = $injectors;
    }

    /**
     * Add injector
     *
     * @param string   $key
     * @param \Closure $value
     */
    public function __set($key, $value)
    {
        $this->injectors[$key] = $value;
    }

    /**
     * Get injector
     *
     * @param string $key
     * @throws BadMethodCallException
     * @return mixed|Closure
     */
    public function __get($key)
    {
        if (isset($this->injectors[$key]) && $this->injectors[$key] instanceof \Closure) {
            return $this->injectors[$key]();
        }

        throw new \BadMethodCallException('Call to undefined injector ' . __CLASS__ . '::' . $key . '()');
    }

    /**
     * Exists injector
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->injectors[$key]);
    }

    /**
     * Delete injector
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->injectors[$key]);
    }

    /**
     * Protect the closure
     *
     * @param callable $closure
     * @return callable
     */
    public function protect(Closure $closure)
    {
        return function () use ($closure) {
            return $closure;
        };
    }

    /**
     * Share the closure
     *
     * @param callable $closure
     * @return callable
     */
    public function share(\Closure $closure)
    {
        $that = $this;
        return function () use ($closure, $that) {
            static $obj;

            if ($obj === null) {
                $obj = $closure($that);
            }

            return $obj;
        };
    }

    /**
     * Extend the injector
     *
     * @param string   $key
     * @param callable $closure
     * @return callable
     * @throws \InvalidArgumentException
     */
    public function extend($key, \Closure $closure)
    {
        if (!isset($this->injectors[$key])) {
            throw new \InvalidArgumentException(sprintf('Injector "%s" is not defined.', $key));
        }

        $factory = $this->injectors[$key];

        if (!($factory instanceof \Closure)) {
            throw new \InvalidArgumentException(sprintf('Injector "%s" does not contain an object definition.', $key));
        }

        $that = $this;
        return $this->injectors[$key] = function () use ($closure, $factory, $that) {
            return $closure(call_user_func_array($factory, func_get_args()), $that);
        };
    }

    /**
     * Call injector
     *
     * @param string $method
     * @param array  $args
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (($closure = $this->$method) instanceof \Closure) {
            return call_user_func_array($closure, $args);
        }

        throw new \BadMethodCallException('Call to undefined injector ' . __CLASS__ . '::' . $method . '()');
    }

    /**
     * Get injector
     *
     * @param string $key
     * @return bool|mixed
     */
    public function raw($key)
    {
        return isset($this->injectors[$key]) ? $this->injectors[$key] : false;
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->injectors);
    }
}