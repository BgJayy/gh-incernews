<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_public_pages_send_security_headers(): void
    {
        $response = $this->get('/robots.txt');

        $response
            ->assertOk()
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeaderMissing('X-Powered-By');

        $this->assertStringContainsString("frame-ancestors 'self'", (string) $response->headers->get('Content-Security-Policy'));
    }

    public function test_admin_login_sends_security_headers(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
