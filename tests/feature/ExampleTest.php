<?php

namespace Inetis\Testing\Tests\Feature;

use Backend\Facades\Backend;
use Backend\Models\User as BackendUser;
use Inetis\Testing\Tests\TestCase;
use RainLab\User\Models\User as RainLabUser;

class ExampleTest extends TestCase
{
    /** @test */
    public function reach_home()
    {
        $this->get('/')
            ->assertSeeText('The demo TEST')
            ->assertStatus(200);
    }

    /** @test */
    public function frontend_auth()
    {
        $user = factory(RainLabUser::class)->create();

        $this->get('/account')
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get('/account')
            ->assertStatus(200);
    }

    /** @test */
    public function backend_auth()
    {
        $user = factory(BackendUser::class)
            ->states('role:developer')
            ->create();

        $this->get(Backend::url())
            ->assertRedirect(Backend::url('backend/auth'));

        $this->actingAs($user)
            ->followingRedirects()
            ->get(Backend::url('backend'))
            ->assertStatus(200)
            ->assertSee('Sign out');
    }
}
