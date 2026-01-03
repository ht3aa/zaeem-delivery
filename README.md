# Zaeem Delivery integration for laravel. 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ht3aa/zaeem-delivery.svg?style=flat-square)](https://packagist.org/packages/ht3aa/zaeem-delivery)
[![Total Downloads](https://img.shields.io/packagist/dt/ht3aa/zaeem-delivery.svg?style=flat-square)](https://packagist.org/packages/ht3aa/zaeem-delivery)

![Zaeem Delivery Integration For Laravel](image.png)


Zaeem Delivery integration for Laravel. You will find all the functionality you need to make shipments with Zaeem Delivery logistics, including store management, shipment creation, and reference data synchronization.

## Installation

You can install the package via composer:

```bash
composer require ht3aa/zaeem-delivery
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="zaeem-delivery-config"
```

Add the following environment variables to your `.env` file:

```env
ZAEEM_DELIVERY_BASE_URL=https://jenni.alzaeemexp.com/api/v2
ZAEEM_DELIVERY_USERNAME=your_username
ZAEEM_DELIVERY_PASSWORD=your_password
ZAEEM_DELIVERY_SYSTEM_CODE=your_system_code
```

## Database Migrations

Publish the migrations:

```bash
php artisan vendor:publish --tag="zaeem-delivery-migrations"
```

Then run the migrations to create the necessary tables:

```bash
php artisan migrate
```

This will create the following tables:
- `zaeem_governorates` - Stores governorate reference data
- `zaeem_cities` - Stores city reference data
- `zaeem_stores` - Stores your Zaeem Delivery store information

## Usage

### Authentication

Before making API calls, you need to authenticate:

```php
use Ht3aa\ZaeemDelivery\Facades\ZaeemDelivery;

ZaeemDelivery::login();
```

### Creating a Store

Create a new store in Zaeem Delivery:

```php
use Ht3aa\ZaeemDelivery\Facades\ZaeemDelivery;

$store = new ZaeemDeliveryStore([
    'store_name' => 'My Store',
    'store_phone' => '1234567890',
    'governorate_id' => 'GOV001',
    'address' => '123 Main Street',
    'latitude' => 31.2001,
    'longitude' => 29.9187,
    'user_id' => auth()->id(),
]);

$createdStore = ZaeemDelivery::createStore($store);

if ($createdStore) {
    echo "Store ID: " . $createdStore->zd_store_id;
    echo "Generated Password: " . $createdStore->zd_generated_password;
}
```

### Creating a Shipment

Create a new shipment:

```php
use Ht3aa\ZaeemDelivery\Facades\ZaeemDelivery;

$shipment = new ZaeemDeliveryShipment([
    // Add your shipment data here
]);

$createdShipment = ZaeemDelivery::createShipment($shipment);

if ($createdShipment) {
    echo "Shipment ID: " . $createdShipment->zd_shipment_id;
    echo "Status: " . $createdShipment->status;
}
```

### Fetching Reference Data

#### Fetch Governorates

Sync governorates from the Zaeem Delivery API:

```bash
php artisan zaeem:fetch-governorates
```

This command will fetch all governorates and store them in the `zaeem_governorates` table.

#### Fetch Cities

Sync cities from the Zaeem Delivery API:

```bash
php artisan zaeem:fetch-cities
```

If the command fails at some point, you can resume from a specific page:

```bash
php artisan zaeem:fetch-cities --start=5
```

### Using Models

#### ZaeemGovernorate Model

```php
use Ht3aa\ZaeemDelivery\Models\ZaeemGovernorate;

// Get all governorates
$governorates = ZaeemGovernorate::all();

// Find by code
$governorate = ZaeemGovernorate::where('code', 'GOV001')->first();
```

#### ZaeemCity Model

```php
use Ht3aa\ZaeemDelivery\Models\ZaeemCity;

// Get all cities
$cities = ZaeemCity::all();

// Find by city ID
$city = ZaeemCity::where('city_id', 123)->first();

// Get cities by governorate
$cities = ZaeemCity::where('governorate_code', 'GOV001')->get();
```

## Available Commands

- `zaeem:fetch-governorates` - Fetch and sync governorates from Zaeem Delivery API
- `zaeem:fetch-cities` - Fetch and sync cities from Zaeem Delivery API (supports `--start` option to resume from a specific page)

## Features

- ✅ Authentication with Zaeem Delivery API
- ✅ Store creation and management
- ✅ Shipment creation
- ✅ Governorate and city reference data synchronization
- ✅ Eloquent models for governorates and cities
- ✅ Database migrations included
- ✅ Artisan commands for data synchronization
- ✅ Configurable API endpoints and credentials

## Requirements

- PHP ^8.4
- Laravel ^11.0 or ^12.0

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Hasan Tahseen](https://github.com/ht3aa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
