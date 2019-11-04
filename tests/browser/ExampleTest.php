<?php

namespace Inetis\Testing\Tests\Browser;

use Backend\Facades\Backend;
use Backend\Models\User as BackendUser;
use Inetis\Testing\Dusk\Browser;
use Inetis\Testing\Tests\DuskTestCase;
use RainLab\User\Models\User as RainLabUser;

class ExampleTest extends DuskTestCase
{
    /** @test */
    public function can_reach_home()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('The demo');
        });
    }

    /** @test */
    public function page_not_found()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/404')
                ->assertSee('Page not found');
        });
    }

    /** @test */
    public function railab_authentication()
    {
        $user = factory(RainLabUser::class)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/account')
                ->assertPathIs('/login');
        });

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit('/account')
                ->assertSee('My Account')
                ->assertValue('input[name="email"]', $user->email)
                ->type('name', 'Test User UPDATED')
                ->press('Save')
                ->waitForReload()
                ->assertValue('input[name="name"]', 'Test User UPDATED')
                ->screenshot('updateuser')
                ->logout();
        });

        $this->assertEquals('Test User UPDATED', $user->reload()->name);
    }

    /** @test */
    public function backend_authentication()
    {
        $user = factory(BackendUser::class)
            ->states('role:publisher')
            ->create();

        $this->browse(function (Browser $browser) {
            $browser->visit(Backend::uri())
                ->assertPathIs(Backend::uri() . '/backend/auth/signin');
        });

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit(Backend::uri())
                ->assertSee('Settings')
                ->click('#layout-mainmenu li.mainmenu-account > a')
                ->clickLink('My account')
                ->assertValue('input[name="User[email]"]', $user->email)
                ->logout();
        });
    }

    /** @test */
    public function frontend_ajax_calculator()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('AJAX framework')
                ->assertPathIs('/demo/ajax')
                ->type('value1', '50')
                ->select('operation', '-')
                ->type('value2', '10')
                ->press('Calculate')
                ->waitForAjax()
                ->assertSeeIn('#result', '40');
        });
    }
}
