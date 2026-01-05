<?php

namespace Ht3aa\ZaeemDelivery;

use Ht3aa\ZaeemDelivery\Models\ZaeemGovernorate;
use Ht3aa\ZaeemDelivery\Models\ZaeemShipment;
use Ht3aa\ZaeemDelivery\Models\ZaeemStore;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZaeemDelivery
{
    protected string $base_url;

    protected string $username;

    protected string $password;

    protected ?string $token = null;

    public function __construct()
    {
        $this->base_url = config('zaeem-delivery.api.base_url');
        $this->username = config('zaeem-delivery.api.username');
        $this->password = config('zaeem-delivery.api.password');
    }

    private function client()
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->token,
        ])
            ->baseUrl($this->base_url);
    }

    public function login()
    {
        $response = $this->client()->post('/auth/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        if ($response->failed()) {
            Log::error('Failed to login to Zaeem Delivery: ' . $response->body());
        }

        $this->token = $response->json('token');
    }

    public function createStore(ZaeemStore $store): ?ZaeemStore
    {

        $response = $this->client()->post('/stores/create', $store->toArray());

        if ($response->failed()) {
            Log::error('Failed to create store in Zaeem Delivery: ' . $response->body());

            return null;
        }

        $store->zd_store_id = $response->json('store_id');
        $store->zd_generated_password = $response->json('generated_password');

        return $store;
    }

    public function createShipment(ZaeemShipment $shipment): ?ZaeemShipment
    {
        $data = [];

        $data['system_code'] = config('zaeem-delivery.api.system_code');
        $data['shipments'] = [
            $shipment->toArray(),
        ];

        $response = $this->client()->post('/shipments/create', $data);

        if ($this->responseFailed($response)) {
            Log::error('Failed to create shipment in Zaeem Delivery: ' . $response->body());

            return null;
        }

        $acceptedShipments = $response->json('accepted_shipments');

        if (! $acceptedShipments) {
            Log::error('Failed to create shipment in Zaeem Delivery: ' . $response->body());

            return null;
        }

        $shipment->zd_shipment_id = $acceptedShipments[0]['shipment_id'];
        $shipment->status = $acceptedShipments[0]['status'];

        return $shipment;
    }

    public function responseFailed($response): bool
    {
        return $response->failed() || $response->json('success') === false;
    }
}
