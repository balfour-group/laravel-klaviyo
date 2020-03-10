<?php

namespace Balfour\LaravelKlaviyo\Jobs;

use Balfour\LaravelKlaviyo\EventInterface;
use Balfour\LaravelKlaviyo\Klaviyo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var EventInterface
     */
    public $event;

    /**
     * Create a new job instance.
     *
     * @param EventInterface $event
     */
    public function __construct(EventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @param Klaviyo $klaviyo
     * @throws \Exception
     */
    public function handle(Klaviyo $klaviyo)
    {
        $klaviyo->trackEvent($this->event);
    }

    /**
     * @param EventInterface $event
     * @param string|null $queue
     */
    public static function enqueue(EventInterface $event, $queue = null)
    {
        if ($queue === null) {
            $queue = config('klaviyo.queue');
        }

        if (config('klaviyo.enabled')) {
            static::dispatch($event)->onQueue($queue);
        }
    }
}
