<?php

namespace Inetis\Testing\Classes;

use Backend\ServiceProvider as BackendServiceProvider;
use Closure;
use Cms\ServiceProvider as CmsServiceProvider;
use October\Rain\Foundation\Providers\ExecutionContextProvider;
use System\ServiceProvider as SystemServiceProvider;

/**
 * Reload OctoberCMS providers that are based on the Request.
 * This middleware is required for HTTP tests work properly on the Backend
 *
 * Without this middleware App::runningInBackend() not work and many backend
 * elements are not registered.
 */
class ReloadProvidersMiddleware
{
    /** @var ExecutionContextProvider */
    private $executionContextProvider;

    /** @var SystemServiceProvider */
    private $systemServiceProvider;

    /** @var CmsServiceProvider */
    private $cmsServiceProvider;

    /** @var BackendServiceProvider */
    private $backendServiceProvider;

    public function __construct()
    {
        $this->executionContextProvider = app()->getProvider(ExecutionContextProvider::class);
        $this->systemServiceProvider = app()->getProvider(SystemServiceProvider::class);
        $this->cmsServiceProvider = app()->getProvider(CmsServiceProvider::class);
        $this->backendServiceProvider = app()->getProvider(BackendServiceProvider::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->executionContextProvider->register();

        // @todo uncomment after OC 460 release (@see https://github.com/octobercms/october/pull/4751)
        // $this->systemServiceProvider->register();

        $this->cmsServiceProvider->register();
        $this->backendServiceProvider->register();

        return $next($request);
    }
}
