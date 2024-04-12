<?php

namespace DGTournaments\Console\Commands\Maintenance;

use Carbon\Carbon;
use DGTournaments\Models\ClaimRequest;
use Illuminate\Console\Command;

class RemoveExpiredClaimRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:claim-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $claimRequest;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ClaimRequest $claimRequest)
    {
        parent::__construct();
        $this->claimRequest = $claimRequest;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->claimRequest->where('created_at', '<', Carbon::now()->subHours(12))->delete();
    }
}
