<?php

namespace Ht3aa\ZaeemDelivery\Repositories;

use Ht3aa\ZaeemDelivery\Models\ZaeemShipment;
use Ht3aa\ZaeemDelivery\Models\ZaeemShipmentUpdate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ZaeemDeliveryShipmentUpdateRepository
{
    public function processUpdates(string $systemCode, array $updates): void
    {
        if ($systemCode !== config('zaeem-delivery.api.system_code')) {
            throw new UnprocessableEntityHttpException('Invalid system code');
        }

        try {
            DB::beginTransaction();
            foreach ($updates as $updateData) {
                $this->processUpdate($updateData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException('Failed to process shipment update');
        }
    }

    private function processUpdate(array $data): void
    {
        try {
            $shipment = ZaeemShipment::where('shipment_number', $data['shipment_number'])
                ->orWhere('external_shipment_id', $data['external_id'] ?? null)
                ->first();

            // Create the update record
            $update = ZaeemShipmentUpdate::create(
                [
                    'zaeem_shipment_id' => $shipment?->id,
                    'updates' => $data,
                ]
            );

            // Update the shipment status if we found a matching shipment
            if ($shipment && isset($data['current_step'])) {
                $shipment->update([
                    'status' => $data['current_step'],
                ]);
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException('Failed to process shipment update');
        }
    }
}
