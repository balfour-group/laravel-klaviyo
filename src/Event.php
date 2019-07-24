<?php

namespace Balfour\LaravelKlaviyo;

class Event extends BaseEvent
{
    /**
     * @var IdentityInterface|string
     */
    protected $identity;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @param IdentityInterface|string $identity
     * @param string $name
     * @param array $properties
     */
    public function __construct($identity, $name, array $properties = [])
    {
        $this->identity = $identity;
        $this->name = $name;
        $this->properties = $properties;
    }

    /**
     * @return IdentityInterface|string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
