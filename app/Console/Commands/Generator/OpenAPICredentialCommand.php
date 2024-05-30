<?php

namespace App\Console\Commands\Generator;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class OpenAPICredentialCommand extends Command
{
    protected $signature = 'open-api-credential
                            {name? : The name of the credential}
                            {--replace : Replace the old id and key from configuration}';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {

            $name = $this->argument('name');
            if (!$name) {
                $this->error("Please enter your credential name!");
                return;
            }

            $credentials = config('open-api.credentials') ?: [];
            if (isset($credentials[$name]) && !$this->option('replace')) {
                if ($credentials[$name]['key'] && $credentials[$name]['id']) {
                    $key = $credentials[$name]['key'];
                    $id = $credentials[$name]['id'];
                } else {
                    $key = Uuid::uuid4()->toString();
                    $id = $this->createID();
                }
            } else {
                $key = Uuid::uuid4()->toString();
                $id = $this->createID();
            }

            $token = $this->createKey($key, $id);

            $this->alert("Please Save ID & Key to config (open-api.php) & .env file");
            $this->info("ID: $id");
            $this->info("Name: $name");
            $this->info("Key: $key");
            $this->info("public Key: $token");

        } catch (\Exception $exception) {
            Log::error($exception);
            $this->error($exception->getMessage());
        }
    }


    /** --- SUB FUNCTIONS --- */

    private function createID()
    {
        $credentials = config('open-api.credentials') ?: [];

        $number = microtime(true);

        return preg_replace('/[^0-9]/', '', $number . (count($credentials) + 1));
    }

    private function createKey(string $key, float $id)
    {
        $cipher = 'AES-128-CBC';

        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        $encryption = openssl_encrypt(Hash::make($key), $cipher, $key . $id, 0, $iv);

        $encryption = base64_encode($iv . $encryption);

        return "_open:$encryption";
    }

}
