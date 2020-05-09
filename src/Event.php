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
     * @var mixed[]
     */
    protected $properties;

    /**
     * @param IdentityInterface|string $identity
     * @param string $name
     * @param mixed[] $properties
     */
    public function __construct($identity, string $name, array $properties = [])
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
