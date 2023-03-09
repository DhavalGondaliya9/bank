<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;

test('it can render the home page', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('it can render the store method', function (): void {
    $response = $this->post(route('store'), [
        'order_payment' => UploadedFile::fake()->image('avatar.csv')->size(100),
        'bank' => UploadedFile::fake()->image('avatar.xlsx')->size(100),
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/');
});
