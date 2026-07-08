<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // Pastikan ini ada
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // Baris ini wajib ada agar tiap tes dapat database bersih

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}