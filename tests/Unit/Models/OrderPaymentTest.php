<?php

declare(strict_types=1);

use App\Models\OrderPayments;

test('it add order payment', function (): void {
    $orderPayment = OrderPayments::factory()->create([
        'amount' => 1000,
    ]);
    expect($orderPayment->amount)->toEqual(1000);
});
