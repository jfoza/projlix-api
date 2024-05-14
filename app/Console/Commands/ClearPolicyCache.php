<?php

namespace App\Console\Commands;

use App\Features\Base\Cache\PolicyCache;
use Illuminate\Console\Command;

class ClearPolicyCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-policy-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpeza do cache de regras do usuário.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        PolicyCache::invalidateAllPolicy();

        info('Cache de regras(policy) limpo.');

        echo "Cache de regras(policy) limpo!\n";
    }
}
