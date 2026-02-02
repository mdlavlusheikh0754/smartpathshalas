<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Homework;
use Carbon\Carbon;

class SeedTenantHomework extends Command
{
    protected $signature = 'tenant:seed-homework {tenant}';
    protected $description = 'Seed homework data for a specific tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant');
        
        // Initialize tenancy for the specific tenant
        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            $this->error("Tenant {$tenantId} not found!");
            return 1;
        }

        tenancy()->initialize($tenant);

        // No demo data - clean production ready state
        // Users can create their own homework assignments
        
        $this->info("Homework seeding skipped - no demo data for production environment");
        $this->info("Users can create their own homework assignments through the interface");
        
        return 0;
    }
}