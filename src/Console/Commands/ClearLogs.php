<?php

namespace Softworx\RocXolid\Console\Commands;

use Illuminate\Console\Command;

/**
 * Command to clear the log files.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rocXolid:logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log files';

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
        try {
            exec('rm ' . storage_path('logs/*.log'));

            $this->comment('Logs have been cleared!');
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}
