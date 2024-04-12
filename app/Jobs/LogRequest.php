<?php

namespace DGTournaments\Jobs;

use DGTournaments\Models\RequestLog;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    protected $user;

    protected $exceptExactUri = [
        '/user/current',
        '/order/current',
        '/cache/bounds',
        '/user/sponsors'
    ];

    protected $exceptPartialUri = [
        '/images'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $user = null)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RequestLog $log)
    {
        if($this->loggableUri())
        {
            $log = $log->create($this->data);

            if(! is_null($this->user)) $log->update(['user_id' => $this->user->id]);
        }
    }

    /**
     * @return bool
     */
    private function loggableUri(): bool
    {
        if($this->userIsAdmin()) return false;

        if(in_array($this->data['uri'], $this->exceptExactUri)) return false;

        foreach ($this->exceptPartialUri as $partial)
        {
            if(strpos($this->data['uri'], $partial) !== false) return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function userIsAdmin() : bool
    {
        return (bool) ! is_null($this->user) && $this->user->isAdmin();
    }
}
