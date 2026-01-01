<?php

namespace Ht3aa\ZaeemDelivery\Commands;

use Illuminate\Console\Command;

class ZaeemDeliveryCommand extends Command
{
    public $signature = 'zaeem-delivery';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
