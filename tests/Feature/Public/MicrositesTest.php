<?php

declare(strict_types=1);

namespace Tests\Feature\Public;

use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class MicrositesTest extends TestCase
{
    use RefreshDatabase;

    public function test_microsites_can_be_rendered(): void
    {
        $this->get('/microsites')
            ->assertStatus(200)
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Microsite/Index')
                    ->has('sites'),
            );
    }

    public function test_microsite_can_render_payment_form(): void
    {
        $site = Microsite::factory(['active' => true])->create();

        $this->get(route('public.microsite.show', $site))
            ->assertStatus(200)
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Microsite/Form')
                    ->has('site'),
            );
    }

    public function test_microsite_does_not_render_payment_form_if_disabled(): void
    {
        $site = Microsite::factory(['active' => false])->create();

        $this->get(route('public.microsite.show', $site))
            ->assertStatus(404);
    }
}
