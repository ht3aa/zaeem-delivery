<?php

namespace Ht3aa\ZaeemDelivery\Commands;

use Ht3aa\ZaeemDelivery\Models\ZaeemGovernorate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchGovernorates extends Command
{
    protected $signature = 'zaeem:fetch-governorates';

    protected $description = 'Fetch and sync governorates from Zaeem Delivery API';

    public function handle(): int
    {
        $this->info('Fetching governorates from API...');

        $response = Http::get('https://jenni.alzaeemexp.com/api/v2/reference/governorates');

        if (! $response->successful()) {
            $this->error('Failed to fetch governorates from API.');

            return self::FAILURE;
        }

        $data = $response->json();

        if (! $data['success'] || empty($data['data'])) {
            $this->error('API returned unsuccessful response or empty data.');

            return self::FAILURE;
        }

        $governorates = $data['data'];
        $count = 0;

        $this->withProgressBar($governorates, function ($governorate) use (&$count) {
            ZaeemGovernorate::updateOrCreate(
                ['code' => $governorate['code']],
                [
                    'global_name' => $governorate['global_name'],
                    'arabic_name' => $governorate['arabic_name'],
                    'description' => $governorate['description'] ?? null,
                ]
            );
            $count++;
        });

        $this->newLine();
        $this->info("Successfully fetched {$count} governorates.");

        return self::SUCCESS;
    }
}
