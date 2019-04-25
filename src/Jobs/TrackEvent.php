<?php

namespace Balfour\LaravelKlaviyo\Jobs;

use Balfour\LaravelKlaviyo\Event;
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
     * @var Event
     */
    public $event;

    /**
     * Create a new job instance.
     *
     * @param Event $event
     */
    public function __construct(Event $event)
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
     * @param Event $event
     * @param string|null $queue
     */
    public static function enqueue(Event $event, $queue = null)
    {
        if ($queue === null) {
            $queue = config('klaviyo.queue');
        }

        static::dispatch($event)->onQueue($queue);
    }
}
