<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry;

/**
 * Dependency injection container
 * Class DependencyInjection
 * @package Raspberry
 */
class DependencyInjection
{
    private $container;
    private $services;

    /**
     * Set service
     * @param $name
     * @param callable $service
     */
    public function set($name, callable $service)
    {
        $this->services[$name] = $service;
    }

    /**
     * Get service
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception('Service not found ' . $name);
        }

        if (!isset($this->container[$name])) {
            $this->container[$name] = $this->services[$name]();
        }

        return $this->container[$name];
    }
}