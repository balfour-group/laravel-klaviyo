<?php

namespace Balfour\LaravelKlaviyo;

class Event extends BaseEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed[]
     */
    protected $properties;

    /**
     * @param string $name
     * @param mixed[] $properties
     */
    public function __construct(string $name, array $properties = [])
    {
        $this->name = $name;
        $this->properties = $properties;
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
