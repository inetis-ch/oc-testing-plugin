<?php

namespace Inetis\Testing\Classes;

use DB;
use Spatie\DbSnapshots\DbDumperFactory;
use Spatie\MigrateFresh\TableDropperFactory;

class DatabaseSnapshot
{
    protected $snapshotsDirectory;

    protected static $snapshot;

    public function __construct()
    {
        $this->snapshotsDirectory = plugins_path('inetis/testing/tests/temp/snapshots/database/');
    }

    public function dump($force = false)
    {
        if (!empty(self::$snapshot) && !$force) {
            return;
        }

        if (!file_exists($directory = $this->snapshotsDirectory)) {
            mkdir($directory, 0777, true);
        }

        DbDumperFactory::createForConnection(config('database.default'))->dumpToFile(
            self::$snapshot = $directory . now()->format('Ymd-H.i.s.u') . '.sql'
        );
    }

    public function restore()
    {
        $tableDropper = TableDropperFactory::create(DB::getDriverName());
        $tableDropper->dropAllTables();

        DB::reconnect();

        DB::connection(config('database.default'))->unprepared(
            file_get_contents(self::$snapshot)
        );
    }
}
