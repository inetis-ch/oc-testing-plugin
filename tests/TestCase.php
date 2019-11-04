<?php

namespace Inetis\Testing\Tests;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /**
     * Set the currently logged in user for the application.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string|null                                $driver
     *
     * @return void
     */
    public function be(UserContract $user, $driver = null)
    {
        if ($user instanceof \RainLab\User\Models\User) {
            $this->app['user.auth']->setUser($user);
        }

        if ($user instanceof \Backend\Models\User) {
            $this->app['backend.auth']->setUser($user);
        }
    }
}
