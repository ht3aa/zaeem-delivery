<?php

namespace Ht3aa\ZaeemDelivery\Commands;

use Ht3aa\ZaeemDelivery\Models\ZaeemCity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchZaeemCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zaeem:fetch-cities {--start=1 : The page number to start from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all cities from Zaeem Delivery API and store them in the database. If the command fails at some page, you can run it again and use the --start option to resume from the last page that was successfully fetched.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Fetching cities from Zaeem Delivery API...');

        $startPage = (int) $this->option('start');
        $currentPage = $startPage;
        $totalPages = 1;
        $totalCities = 0;
        $failedPages = [];

        $progressBar = null;

        // First, fetch page 1 to get total pages info
        $firstResponse = $this->fetchCitiesPage(1);
        if (! $firstResponse) {
            $this->error('Failed to fetch initial page info');

            return self::FAILURE;
        }

        $totalPages = $firstResponse['total_pages'];
        $totalCount = $firstResponse['total_count'];
        $this->info("Total cities: {$totalCount}, Total pages: {$totalPages}");
        $this->info("Starting from page: {$startPage}");

        $pagesToProcess = $totalPages - $startPage + 1;
        $progressBar = $this->output->createProgressBar($pagesToProcess);
        $progressBar->start();

        // If starting from page 1, process the first response we already have
        if ($startPage === 1) {
            $cities = $firstResponse['data'] ?? [];
            foreach ($cities as $cityData) {
                ZaeemCity::firstOrCreate(
                    ['city_id' => $cityData['city_id']],
                    [
                        'city_name' => $cityData['city_name'],
                        'governorate_code' => $cityData['governorate_code'],
                    ]
                );
                $totalCities++;
            }
            $progressBar->advance();
            $currentPage++;

            if ($currentPage <= $totalPages) {
                sleep(2);
            }
        }

        while ($currentPage <= $totalPages) {
            $response = $this->fetchCitiesPage($currentPage);

            if (! $response) {
                $this->warn(" Failed to fetch page {$currentPage}");
                $failedPages[] = $currentPage;
                Log::error("Failed to fetch cities page {$currentPage}");
            } else {
                $cities = $response['data'] ?? [];

                foreach ($cities as $cityData) {
                    ZaeemCity::firstOrCreate(
                        ['city_id' => $cityData['city_id']],
                        [
                            'city_name' => $cityData['city_name'],
                            'governorate_code' => $cityData['governorate_code'],
                        ]
                    );
                    $totalCities++;
                }
            }

            $progressBar->advance();
            $currentPage++;

            if ($currentPage <= $totalPages) {
                sleep(2);
            }
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("Successfully fetched and stored {$totalCities} cities.");

        if (count($failedPages) > 0) {
            $this->warn('Failed pages: ' . implode(', ', $failedPages));
            Log::error('Failed to fetch cities for pages: ' . implode(', ', $failedPages));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function fetchCitiesPage(int $page, int $retries = 3): ?array
    {
        $baseUrl = config('services.zaeem-delivery.base_url');

        for ($attempt = 1; $attempt <= $retries; $attempt++) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->baseUrl($baseUrl)
                ->get('/reference/cities', [
                    'page' => $page,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("Attempt {$attempt} failed for page {$page}: " . $response->body());

            if ($attempt < $retries) {
                sleep(2);
            }
        }

        Log::error('Failed to fetch cities from Zaeem Delivery after ' . $retries . ' attempts: ' . $response->body());

        return null;
    }
}
