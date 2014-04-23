<?php
require_once 'paths.php';
require _CORE_DIR_.'core.php';
\Asgard\Core\App::loadDefaultApp();

\Asgard\Utils\FileManager::copy(__DIR__.'/app/slideshow', _DIR_.'app/slideshow');
\Asgard\Utils\FileManager::copy(__DIR__.'/web/slideshow', _DIR_.'web/slideshow');

\Asgard\Orm\Libs\MigrationsManager::addMigrationFile(__DIR__.'/migrations/Slideshow.php');
\Asgard\Orm\Libs\MigrationsManager::migrate('Slideshow');
