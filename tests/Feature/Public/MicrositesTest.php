<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class MicrositesTest extends TestCase
{
    public function test_microsites_can_be_rendered(): void
    {
        $this->get('/microsites')
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Microsite/Index')
                ->has('sites')
            );
    }
}
