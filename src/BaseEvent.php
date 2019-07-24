<?php

namespace Balfour\LaravelKlaviyo;

use Balfour\LaravelKlaviyo\Jobs\TrackEvent;

abstract class BaseEvent implements EventInterface
{
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
