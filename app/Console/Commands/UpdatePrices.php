<?php
// app/Console/Commands/UpdatePrices.php

namespace App\Console\Commands;

use App\Services\PriceService;
use Illuminate\Console\Command;

class UpdatePrices extends Command
{
    protected $signature = 'prices:update';
    protected $description = 'Update all asset prices from APIs';

    protected $priceService;

    public function __construct(PriceService $priceService)
    {
        parent::__construct();
        $this->priceService = $priceService;
    }

    public function handle()
    {
        $this->info('Starting price update...');
        
        try {
            $this->priceService->updateAllAssets();
            $this->info('✅ Prices updated successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Error updating prices: ' . $e->getMessage());
        }
    }
}