<?php

declare(strict_types=1);

defined('ROOT') or define('ROOT', __DIR__);

/** set working directory */
if (getcwd() !== ROOT) {
    chdir(ROOT);
}

/** load autoload composer */
require 'vendor/autoload.php';

/** Load .env file for read FASTER_ENVIRONMENT and FASTER_CONNECTION */
\Dotenv\Dotenv::createImmutable(ROOT)->load();

/** set config folder and init component */
\Faster\Helper\Config::init('app/config', $_ENV['FASTER_ENVIRONMENT']);

/** set db configuration */
\Faster\Helper\Db::init(config('db'), $_ENV['FASTER_CONNECTION']);

$action = null;
if ($argc > 1) {
    $action = $argv[1];
}

if (!\Faster\Component\Enums\MigrationActionEnum::hasValue($action)) {
    echo "Available action is :" . implode(", ", \Faster\Component\Enums\MigrationActionEnum::values());
}

/** run console application */
make(\Faster\Console\Application::class, [make(\Faster\Console\MigrationCommand::class, [db(), 'migrations', 'app/migration']), $action])->run();
