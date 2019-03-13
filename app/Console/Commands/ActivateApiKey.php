<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Illuminate\Console\Command;

class ActivateApiKey extends Command
{
    
    /**
     * Error messages
     */
    const MESSAGE_ERROR_INVALID_NAME        = 'Invalid name.';
    const MESSAGE_ERROR_NAME_DOES_NOT_EXIST = 'Key does not exist.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:activate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate an API key by name';

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
        $name = $this->argument('name');

        $error = $this->validateName($name);

        if ($error) {
            $this->error($error);
            return;
        }

        $key = ApiKey::where('name', $name)->first();

        if ($key->active) {
            $this->info('Key "' . $name . '" is already active');
            return;
        }

        $key->active = 1;
        $key->save();

        $this->info('Activated key: ' . $name);
    }

    /**
     * Validate name
     *
     * @param string $name
     * @return string
     */
    protected function validateName($name) {
        if (!ApiKey::isValidName($name)) {
            return self::MESSAGE_ERROR_INVALID_NAME;
        }

        if (!ApiKey::nameExists($name)) {
            return self::MESSAGE_ERROR_NAME_DOES_NOT_EXIST;
        }
        
        return null;
    }
}
