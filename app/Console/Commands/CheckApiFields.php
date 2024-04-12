<?php

namespace DGTournaments\Console\Commands;

use DGTournaments\Mail\Admin\ApiFieldsChanged;
use DGTournaments\Services\Pdga\PdgaApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckApiFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:check-api-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $tournamentFields = [
        "tournament_id",
        "tournament_name",
        "city",
        "state_prov",
        "country",
        "latitude",
        "longitude",
        "start_date",
        "end_date",
        "class",
        "tier",
        "format",
        "tournament_director",
        "tournament_director_pdga_number",
        "asst_tournament_director",
        "asst_tournament_director_pdga_number",
        "event_email",
        "event_phone",
        "event_url",
        "website_url",
        "registration_url",
        "last_modified"
    ];

    protected $pdgaApi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PdgaApi $pdgaApi)
    {
        parent::__construct();
        $this->pdgaApi = $pdgaApi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fields = $this->pdgaApi->getTournamentFields();

        // Fields removed
        $diff1 = array_diff($this->tournamentFields, $fields);

        // Fields added
        $diff2 = array_diff($fields, $this->tournamentFields);

        if(! (empty($diff1) && empty($diff2)))
        {
            Mail::to('admin@dgtournaments.com')->send(new ApiFieldsChanged($diff1, $diff2));
        }
    }
}
