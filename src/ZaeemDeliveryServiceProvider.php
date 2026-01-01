<?php

namespace Ht3aa\ZaeemDelivery;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ht3aa\ZaeemDelivery\Commands\ZaeemDeliveryCommand;

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
            ->hasMigration('create_zaeem_delivery_table')
            ->hasCommand(ZaeemDeliveryCommand::class);
    }
}
