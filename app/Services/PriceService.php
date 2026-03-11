<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PriceService
{
    /**
     * دریافت قیمت‌های لحظه‌ای از والکس
     */
    public function getPrices(): array
    {
        // کش ۵ دقیقه‌ای
        return Cache::remember('all_prices', 300, function () {
            
            // دریافت داده از والکس
            $data = $this->fetchFromWallex();
            
            if ($data && isset($data['result']['symbols'])) {
                return $this->formatPrices($data['result']['symbols']);
            }
            
            // اگر والکس کار نکرد، از کش قدیمی یا پیش‌فرض استفاده کن
            return $this->getDefaultPrices();
        });
    }

    /**
     * دریافت داده از والکس
     */
    private function fetchFromWallex(): ?array
    {
        try {
            Log::info('Fetching from Wallex...');
            
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->retry(2, 500)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])
                ->get('https://api.wallex.ir/v1/markets');

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Wallex response received');
                return $data;
            }
            
            Log::warning('Wallex failed', ['status' => $response->status()]);
            
        } catch (\Exception $e) {
            Log::error('Wallex error: ' . $e->getMessage());
        }
        
        return null;
    }

    /**
     * فرمت کردن قیمت‌ها
     */
    private function formatPrices($symbols): array
    {
        // قیمت تتر (USDT) - مبنای دلار
        $usdt = $symbols['USDT-IRT']['stats']['lastPrice'] ?? 600000;
        $usdt = (int) $usdt;
        
        // قیمت بیت‌کوین
        $btc = $symbols['BTC-IRT']['stats']['lastPrice'] ?? 0;
        $btc = (int) $btc;
        
        // قیمت اتریوم
        $eth = $symbols['ETH-IRT']['stats']['lastPrice'] ?? 0;
        $eth = (int) $eth;
        
        // قیمت‌های تقریبی ارزها (بر اساس تتر)
        return [
            'currency' => [
                'usd' => $usdt,
                'eur' => (int) ($usdt * 1.08), // نرخ تقریبی
                'gbp' => (int) ($usdt * 1.25), // نرخ تقریبی
                'try' => (int) ($usdt / 33),   // نرخ تقریبی لیر
                'aed' => (int) ($usdt / 3.67), // نرخ تقریبی درهم
                'cny' => (int) ($usdt / 7.2),  // نرخ تقریبی یوان
                'jpy' => (int) ($usdt / 150),  // نرخ تقریبی ین
                'source' => 'wallex',
                'updated_at' => now(),
            ],
            'crypto' => [
                'btc' => $btc,
                'eth' => $eth,
                'usdt' => $usdt,
                'source' => 'wallex',
                'updated_at' => now(),
            ],
            'gold' => [
                'geram18' => $this->calculateGoldPrice($usdt),
                'geram24' => $this->calculateGoldPrice($usdt) * 1.2,
                'source' => 'calculated',
                'updated_at' => now(),
            ],
            'coin' => [
                'sekeb' => $this->calculateCoinPrice('sekeb', $usdt),
                'nim' => $this->calculateCoinPrice('nim', $usdt),
                'rob' => $this->calculateCoinPrice('rob', $usdt),
                'gerami' => $this->calculateCoinPrice('gerami', $usdt),
                'source' => 'calculated',
                'updated_at' => now(),
            ]
        ];
    }

    /**
     * محاسبه قیمت طلا بر اساس دلار
     */
    private function calculateGoldPrice($usdPrice): int
    {
        // هر اونس طلا = 2000 دلار (تقریبی)
        // هر اونس = 31.1 گرم
        // هر گرم طلای 18 عیار = (انس طلا / 31.1) * (18/24) * دلار
        $ounceGoldPrice = 2000; // قیمت تقریبی هر اونس طلا به دلار
        $gramPrice = ($ounceGoldPrice / 31.1) * 0.75; // قیمت هر گرم به دلار
        return (int) ($gramPrice * $usdPrice);
    }

    /**
     * محاسبه قیمت سکه بر اساس دلار
     */
    private function calculateCoinPrice($type, $usdPrice): int
    {
        // وزن سکه‌ها به گرم
        $weights = [
            'sekeb' => 8.133,  // سکه تمام
            'nim' => 4.0665,    // نیم سکه
            'rob' => 2.03325,   // ربع سکه
            'gerami' => 1.0,    // سکه گرمی
        ];
        
        $weight = $weights[$type] ?? 1;
        $goldPricePerGram = $this->calculateGoldPrice($usdPrice);
        
        // قیمت سکه = وزن * قیمت هر گرم + حق ضرب (15% تقریبی)
        return (int) ($weight * $goldPricePerGram * 1.15);
    }

    /**
     * دریافت قیمت یک دارایی خاص
     */
    public function getAssetPrice(string $type, ?string $name = null): float
    {
        $prices = $this->getPrices();

        switch ($type) {
            case 'dollar':
            case 'usd':
                return $prices['currency']['usd'] ?? 600000;
            
            case 'euro':
            case 'eur':
                return $prices['currency']['eur'] ?? 650000;
            
            case 'pound':
            case 'gbp':
                return $prices['currency']['gbp'] ?? 750000;
            
            case 'lira':
            case 'try':
                return $prices['currency']['try'] ?? 18000;
            
            case 'dirham':
            case 'aed':
                return $prices['currency']['aed'] ?? 163000;
            
            case 'bitcoin':
            case 'btc':
                return $prices['crypto']['btc'] ?? 0;
            
            case 'ethereum':
            case 'eth':
                return $prices['crypto']['eth'] ?? 0;
            
            case 'tether':
            case 'usdt':
                return $prices['crypto']['usdt'] ?? 600000;
            
            case 'gold':
                if (str_contains($name ?? '', 'سکه تمام')) {
                    return $prices['coin']['sekeb'] ?? 28000000;
                }
                if (str_contains($name ?? '', 'نیم سکه')) {
                    return $prices['coin']['nim'] ?? 14500000;
                }
                if (str_contains($name ?? '', 'ربع سکه')) {
                    return $prices['coin']['rob'] ?? 9500000;
                }
                return $prices['gold']['geram18'] ?? 3500000;
            
            default:
                return 0;
        }
    }

    /**
     * آپدیت خودکار همه دارایی‌ها
     */
    public function updateAllAssets(): void
    {
        try {
            if (!class_exists('\App\Models\Asset')) {
                Log::warning('Asset model not found');
                return;
            }

            $assets = \App\Models\Asset::all();
            $prices = $this->getPrices();

            foreach ($assets as $asset) {
                $oldValue = $asset->current_value ?? $asset->value ?? 0;
                $newPrice = $this->getAssetPrice($asset->type, $asset->name);
                
                $asset->current_value = ($asset->amount ?? 1) * $newPrice;
                $asset->profit_loss = $asset->current_value - ($asset->purchase_price ?? 0);
                $asset->profit_loss_percentage = $asset->purchase_price > 0 
                    ? round(($asset->profit_loss / $asset->purchase_price) * 100, 2) 
                    : 0;
                
                $asset->save();

                if (class_exists('\App\Models\PriceHistory')) {
                    \App\Models\PriceHistory::create([
                        'asset_id' => $asset->id,
                        'old_value' => $oldValue,
                        'new_value' => $asset->current_value,
                        'price' => $newPrice,
                        'source' => 'wallex',
                    ]);
                }
            }

            Log::info('Assets updated successfully via Wallex');
            
        } catch (\Exception $e) {
            Log::error('Error updating assets: ' . $e->getMessage());
        }
    }

    /**
     * قیمت‌های پیش‌فرض
     */
    private function getDefaultPrices(): array
    {
        return [
            'currency' => [
                'usd' => 600000,
                'eur' => 650000,
                'gbp' => 750000,
                'try' => 18000,
                'aed' => 163000,
                'cny' => 83000,
                'source' => 'default',
                'updated_at' => now(),
            ],
            'crypto' => [
                'btc' => 0,
                'eth' => 0,
                'usdt' => 600000,
                'source' => 'default',
                'updated_at' => now(),
            ],
            'gold' => [
                'geram18' => 3500000,
                'geram24' => 4200000,
                'source' => 'default',
                'updated_at' => now(),
            ],
            'coin' => [
                'sekeb' => 28000000,
                'nim' => 14500000,
                'rob' => 9500000,
                'gerami' => 7000000,
                'source' => 'default',
                'updated_at' => now(),
            ]
        ];
    }
}