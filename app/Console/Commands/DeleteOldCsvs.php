<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldCsvs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old csv files';

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
     * @return mixed
     */
    public function handle()
    {

        $files = Storage::disk('public_app')->files('/csv');

        foreach ($files as $file){

            $time = Storage::disk('public_app')->lastModified($file);

            $fileTime = Carbon::createFromTimestamp($time);

            //Only delete yesterday files
            if(Carbon::now()->diffInDays($fileTime) == 1){
                Storage::disk('public_app')->delete($file);
            }
        }

        $this->info('Done!');

    }
}
