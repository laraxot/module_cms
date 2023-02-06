<?php

declare(strict_types=1);

namespace Modules\Cms\Tests\Unit;

use Tests\TestCase;

class DashboardTest extends TestCase {
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRouteHome() {
        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertViewIs('pub_theme::home');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRouteLogin() {
        $response = $this->get('/it/login');

        $response->assertSuccessful();
        $response->assertViewIs('pub_theme::auth.login');
    }
}
