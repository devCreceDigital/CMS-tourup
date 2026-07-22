<?php

namespace Tests\Feature;

use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_uninstalled_application_redirects_to_the_installer(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/install');
    }

    public function test_installed_application_home_page_is_reachable(): void
    {
        Agency::create([
            'name' => 'Test Agency',
            'email' => 'agency@example.com',
            'phone' => '000000000',
            'active_theme' => 'ethos_earth',
            'is_installed' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
    }
}
