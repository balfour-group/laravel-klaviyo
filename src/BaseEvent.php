<?php

namespace Balfour\LaravelKlaviyo;

use Balfour\LaravelKlaviyo\Jobs\TrackEvent;

abstract class BaseEvent implements EventInterface
{
    /**
     * @param string|IdentityInterface $identity
     * @param string $queue
     */
    public function enqueue($identity, string $queue = 'klaviyo'): void
    {
        if (config('klaviyo.enabled')) {
            TrackEvent::enqueue($identity, $this, $queue);
        }
    }

    /**
     * @param IdentityInterface|string $identity
     * @throws \Exception
     */
    public function fire($identity): void
    {
        if (config('klaviyo.enabled')) {
            $klaviyo = app(Klaviyo::class); /** @var Klaviyo $klaviyo */
            $klaviyo->trackEvent($identity, $this);
        }
    }
}
