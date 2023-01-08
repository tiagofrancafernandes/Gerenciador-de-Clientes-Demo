<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build and seed all table from fresh.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!in_array(app()->environment(), ['local', 'staging'], true)) {
            $this->error('This command is disabled on production.');

            return;
        }

        if ($this->confirm('This will cleal ALL database Do you wish to continue?')) {
            $this->call('migrate:fresh', ['step']);
            $this->line('------');
            $this->call('db:seed');
        }
    }
}
