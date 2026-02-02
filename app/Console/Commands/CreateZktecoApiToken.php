<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateZktecoApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zkteco:create-token 
                            {email : The email of the user to create token for}
                            {--device-id= : The device ID for the token}
                            {--name= : Custom token name}
                            {--expires= : Token expiration in days (default: never)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an API token for ZKTeco device integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $deviceId = $this->option('device-id');
        $tokenName = $this->option('name');
        $expireDays = $this->option('expires');

        // Find user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // Generate token name
        if (!$tokenName) {
            $tokenName = $deviceId ? "ZKTeco Device: {$deviceId}" : 'ZKTeco API Token';
        }

        // Create token
        $tokenBuilder = $user->createToken($tokenName, ['zkteco:*']);
        
        // Set expiration if specified
        if ($expireDays) {
            $tokenBuilder->token->expires_at = now()->addDays((int) $expireDays);
            $tokenBuilder->token->save();
        }

        $token = $tokenBuilder->plainTextToken;

        // Display token information
        $this->info('ZKTeco API Token Created Successfully!');
        $this->line('');
        $this->line("User: {$user->name} ({$user->email})");
        $this->line("Token Name: {$tokenName}");
        
        if ($expireDays) {
            $this->line("Expires: " . now()->addDays((int) $expireDays)->format('Y-m-d H:i:s'));
        } else {
            $this->line("Expires: Never");
        }
        
        $this->line('');
        $this->warn('IMPORTANT: Save this token securely. It will not be shown again.');
        $this->line('');
        $this->info("API Token: {$token}");
        $this->line('');
        
        // Show usage example
        $this->comment('Usage Example:');
        $this->line('curl -H "Authorization: Bearer ' . $token . '" \\');
        $this->line('     -H "Content-Type: application/json" \\');
        $this->line('     https://your-domain.com/api/v1/zkteco/devices');
        $this->line('');
        
        // Update config file suggestion
        if ($deviceId) {
            $this->comment('Update your Python config.yaml:');
            $this->line('api:');
            $this->line('  base_url: "https://your-domain.com/api/v1"');
            $this->line("  token: \"{$token}\"");
            $this->line('');
        }

        return 0;
    }
}