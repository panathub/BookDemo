<?php

namespace Tests\Feature;

use Tests\TestCase;

class LogViewerAuthTest extends TestCase
{
    public function test_guest_is_redirected_from_log_viewer_page()
    {
        $this->get('/admin/log-viewer')->assertRedirect('/login');
    }

    public function test_guest_cannot_reach_log_viewer_api()
    {
        $status = $this->get('/admin/log-viewer/api/folders')->getStatusCode();

        $this->assertContains($status, [302, 401]);
    }
}
