<?php

namespace Inetis\Testing\Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Inetis\Testing\Classes\DatabaseSnapshot;
use Inetis\Testing\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $databaseSnapshot;

    /** @var string */
    private $originalEnvFile;

    /** @var string */
    private $envFile;

    public function setUp()
    {
        parent::setUp();

        $this->databaseSnapshot = new DatabaseSnapshot;
        $this->databaseSnapshot->dump();

        Browser::$storeScreenshotsAt = __DIR__ . '/browser/screenshots';
        Browser::$storeConsoleLogAt = __DIR__ . '/browser/console';

        $this->setDuskEnv();
    }

    public function tearDown()
    {
        $this->databaseSnapshot->restore();

        $this->restoreEnv();

        parent::tearDown();
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Create a new Browser instance.
     *
     * @param \Facebook\WebDriver\Remote\RemoteWebDriver $driver
     *
     * @return \Laravel\Dusk\Browser
     */
    protected function newBrowser($driver)
    {
        return new Browser($driver);
    }

    private function setDuskEnv()
    {
        $this->envFile = base_path('.env');

        if (file_exists($this->envFile)) {
            $this->originalEnvFile = base_path('.env.' . str_replace('.', '', microtime(true)));

            rename($this->envFile, $this->originalEnvFile);
        }

        file_put_contents($this->envFile, 'APP_ENV=dusk');
    }

    private function restoreEnv()
    {
        unlink($this->envFile);

        if (!empty($this->originalEnvFile)) {
            rename($this->originalEnvFile, $this->envFile);
        }
    }
}
