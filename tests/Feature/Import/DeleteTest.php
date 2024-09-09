<?php

declare(strict_types=1);

namespace Tests\Feature\Import;

use Tests\TestCase;

class DeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
