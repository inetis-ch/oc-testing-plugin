<?php

namespace Inetis\Testing\Dusk;

use Laravel\Dusk\Browser as BaseBrowser;

class Browser extends BaseBrowser
{
    /**
     * Log into the application using a given user ID or email.
     *
     * @param object|string $userId
     * @param string        $provider
     *
     * @return $this
     */
    public function loginAs($userId, $provider = null)
    {
        if (empty($provider) && $userId instanceof \RainLab\User\Models\User) {
            $provider = 'rainlab.user';
        }

        if (empty($provider) && $userId instanceof \Backend\Models\User) {
            $provider = 'backend';
        }

        $userId = method_exists($userId, 'getKey') ? $userId->getKey() : $userId;

        return $this->visit(rtrim('/_dusk/login/' . $userId . '/' . $provider, '/'));
    }


    /**
     * Wait for the AJAX request end
     *
     * @param  int  $seconds
     * @return $this
     */
    public function waitForAjax($seconds = null)
    {
        $this->waitFor('.stripe-loading-indicator.loaded', $seconds);

        return $this;
    }
}
