<?php

namespace Ht3aa\ZaeemDelivery\Controllers;

use Ht3aa\ZaeemDelivery\Controllers\Requests\ZaeemDeliveryWebhookRequest;
use Ht3aa\ZaeemDelivery\Repositories\ZaeemDeliveryShipmentUpdateRepository;

class ZaeemDeliveryWebhookController
{
    public function __construct(protected ZaeemDeliveryShipmentUpdateRepository $repository) {}

    public function store(ZaeemDeliveryWebhookRequest $request): void
    {
        $validated = $request->validated();

        $this->repository->processUpdates($validated['system_code'], $validated['updates']);
    }
}
