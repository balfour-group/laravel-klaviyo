<?php

namespace Balfour\LaravelKlaviyo;

use Balfour\LaravelKlaviyo\Jobs\TrackEvent;

class Event
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

    /**
     * @param string $queue
     */
    public function enqueue($queue = 'klaviyo')
    {
        if (config('klaviyo.enabled')) {
            TrackEvent::enqueue($this, $queue);
        }
    }

    /**
     * @throws \Exception
     */
    public function fire()
    {
        if (config('klaviyo.enabled')) {
            $klaviyo = app(Klaviyo::class); /** @var Klaviyo $klaviyo */
            $klaviyo->trackEvent($this);
        }
    }
}
