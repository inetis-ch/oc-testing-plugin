<?php

namespace Inetis\Testing\Http\Controllers;

class DuskUserController
{
    /**
     * Retrieve the authenticated user identifier and class name.
     *
     * @param string $provider
     *
     * @return array
     */
    public function user($provider)
    {
        $user = $this->getAuthManagerForProvider($provider)->getUser();

        if (!$user) {
            return [];
        }

        return [
            'id'        => $user->getAuthIdentifier(),
            'className' => get_class($user),
        ];
    }

    /**
     * Login using the given user ID / email.
     *
     * @param string $userId
     * @param string $provider
     *
     * @throws \October\Rain\Auth\AuthException
     */
    public function login($userId, $provider)
    {
        $model = $this->modelForProvider($provider);

        if (str_contains($userId, '@')) {
            $user = (new $model)->where('email', $userId)->first();
        } else {
            $user = (new $model)->find($userId);
        }

        $this->getAuthManagerForProvider($provider)->login($user);
    }

    /**
     * Log the user out of the application.
     *
     * @param string $guard
     */
    public function logout($provider = null)
    {
        if ($provider) {
            $this->getAuthManagerForProvider($provider)->logout();

            return;
        }

        app('backend.auth')->logout();

        if (class_exists(\RainLab\User\Models\User::class)) {
            app('user.auth')->logout();
        }
    }

    /**
     * Get the model for the given provider.
     *
     * @param string $provider
     *
     * @return string
     */
    protected function modelForProvider($provider)
    {
        switch ($provider) {
            case 'rainlab.user':
            case 'frontend':
                return \RainLab\User\Models\User::class;
            case 'backend':
                return \Backend\Models\User::class;
        }
    }

    /**
     * @param $provider
     *
     * @return \October\Rain\Auth\Manager
     */
    protected function getAuthManagerForProvider($provider)
    {
        switch ($provider) {
            case 'rainlab.user':
            case 'frontend':
                return app('user.auth');
            case 'backend':
                return app('backend.auth');
        }
    }
}
