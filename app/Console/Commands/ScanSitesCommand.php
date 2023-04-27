<?php

namespace App\Console\Commands;

use App\Contracts\ScanSiteContract;
use Illuminate\Console\Command;

class ScanSitesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:scansite';

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
    public function handle(ScanSiteContract $action)
    {
        $action->scanSites();
    }
}
