<?php

namespace App\Console\Commands;

use App\Actions\ScanSiteAction;
use Illuminate\Console\Command;

class ScanSitesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:scansite {type=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сканировыание сайтов';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ScanSiteAction $action)
    {
        $type = $this->argument('type');

        if ($type == 'all')
            $action->scanSite(true);
        else
            $action->scanSite();

        return 0;
    }
}
