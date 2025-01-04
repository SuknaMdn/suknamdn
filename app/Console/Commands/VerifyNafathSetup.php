<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyNafathSetup extends Command
{
    protected $signature = 'nafath:verify-setup';
    protected $description = 'Verify Nafath certificates and keys setup';

    public function handle()
    {
        $this->info('Verifying Nafath setup...');

        // Create directories if they don't exist
        $this->createRequiredDirectories();

        $certPath = storage_path('certs/certificate.cer');
        $keyPath = storage_path('certs/private.key');

        // Check certificate
        if (file_exists($certPath)) {
            $this->info('✓ Certificate found at: ' . $certPath);
            $this->info('Certificate permissions: ' . decoct(fileperms($certPath) & 0777));
        } else {
            $this->error('✗ Certificate not found at: ' . $certPath);
            $this->createDummyFiles($certPath, 'CERTIFICATE');
        }

        // Check private key
        if (file_exists($keyPath)) {
            $this->info('✓ Private key found at: ' . $keyPath);
            $this->info('Private key permissions: ' . decoct(fileperms($keyPath) & 0777));
        } else {
            $this->error('✗ Private key not found at: ' . $keyPath);
            $this->createDummyFiles($keyPath, 'PRIVATE KEY');
        }

        // Check read permissions
        if (is_readable($certPath)) {
            $this->info('✓ Certificate is readable');
        } else {
            $this->error('✗ Certificate is not readable');
        }

        if (is_readable($keyPath)) {
            $this->info('✓ Private key is readable');
        } else {
            $this->error('✗ Private key is not readable');
        }

        // Verify ownership - macOS compatible
        $this->checkFileOwnership($certPath, $keyPath);
    }

    private function createRequiredDirectories()
    {
        $certsPath = storage_path('certs');

        if (!file_exists($certsPath)) {
            mkdir($certsPath, 0755, true);
            $this->info('Created certificates directory at: ' . $certsPath);
        }
    }

    private function createDummyFiles($path, $type)
    {
        if ($this->confirm("Would you like to create a dummy {$type} file for testing?")) {
            $content = "DUMMY {$type} FILE - Replace this with your actual {$type} from Elm\n";
            file_put_contents($path, $content);
            chmod($path, 0600);
            $this->info("Created dummy {$type} file at: {$path}");
        }
    }

    private function checkFileOwnership($certPath, $keyPath)
    {
        // Check if we're on macOS/Linux (POSIX)
        if (function_exists('posix_getpwuid')) {
            if (file_exists($certPath)) {
                $certOwner = posix_getpwuid(fileowner($certPath));
                $this->info('Certificate owner: ' . $certOwner['name']);
            }

            if (file_exists($keyPath)) {
                $keyOwner = posix_getpwuid(fileowner($keyPath));
                $this->info('Private key owner: ' . $keyOwner['name']);
            }
        } else {
            // Fallback for non-POSIX systems
            $this->info('File ownership check not available on this system');
        }
    }
}
