<?php

namespace Balfour\LaravelKlaviyo;

use Balfour\LaravelKlaviyo\Jobs\TrackEvent;
use Illuminate\Queue\SerializesModels;

abstract class BaseEvent implements EventInterface
{
    use SerializesModels;

    /**
     * @param string $queue
     */
    public function enqueue(string $queue = 'klaviyo'): void
    {
        if (config('klaviyo.enabled')) {
            TrackEvent::enqueue($this, $queue);
        }
    }

    /**
     * @throws \Exception
     */
    public function fire(): void
    {
        if (config('klaviyo.enabled')) {
            $klaviyo = app(Klaviyo::class); /** @var Klaviyo $klaviyo */
            $klaviyo->trackEvent($this);
        }
    }
}
