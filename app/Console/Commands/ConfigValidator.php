<?php

namespace App\Console\Commands;

use ErrorException;
use Illuminate\Console\Command;

class ConfigValidator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git_replicator:validate_config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Config.json Validator.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws ErrorException
     */
    public function handle()
    {
        // @todo check if json for all required fields, or throw an Error.
        json_decode(file_get_contents(base_path('config.json')), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ErrorException("Invalid config.json - " . json_last_error_msg());
        }
    }
}
