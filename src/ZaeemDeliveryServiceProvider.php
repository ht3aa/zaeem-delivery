<?php

namespace Ht3aa\ZaeemDelivery;

use Ht3aa\ZaeemDelivery\Commands\FetchGovernorates;
use Ht3aa\ZaeemDelivery\Commands\FetchZaeemCities;
use Ht3aa\ZaeemDelivery\Commands\ZaeemDeliveryCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ZaeemDeliveryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('zaeem-delivery')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations(
                'create_zaeem_cities_table',
                'create_zaeem_governorates_table',
                'create_zaeem_stores_table',
            )
            ->hasCommands(
                FetchZaeemCities::class,
                FetchGovernorates::class,
            );
    }
}
