<?php

namespace App\Console\Commands;

use App\Jobs\ChangeNotificateJob;
use Illuminate\Console\Command;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendchange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка email с изменениями';

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
    public function handle()
    {
        ChangeNotificateJob::dispatch()->onQueue('emailer');
    }
}
