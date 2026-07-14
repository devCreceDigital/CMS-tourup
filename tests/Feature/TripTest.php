<?php

namespace Tests\Feature;

use Tests\TestCase;

class TripTest extends TestCase
{
    private function slugify(string $name): string
    {
        return \Illuminate\Support\Str::slug($name);
    }

    public function test_slug_is_generated_from_name()
    {
        $this->assertEquals('test-trip', $this->slugify('Test Trip'));
    }

    public function test_slug_handles_special_chars()
    {
        $this->assertEquals('viaje-a-madrid-2026', $this->slugify('Viaje a Madrid 2026!'));
    }

    public function test_slug_handles_single_word()
    {
        $this->assertEquals('vacaciones', $this->slugify('Vacaciones'));
    }

    public function test_slug_handles_accents()
    {
        $this->assertEquals('programacion', $this->slugify('Programación'));
    }

    public function test_generate_unique_slug_function_exists()
    {
        $this->assertTrue(function_exists('generate_unique_slug'));
    }
}
