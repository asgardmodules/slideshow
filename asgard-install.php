<?php
require_once __DIR__.'/../utils/FileManager.php';

\Asgard\Utils\FileManager::copy(__DIR__.'/app/slideshow', 'app/slideshow');
\Asgard\Utils\FileManager::copy(__DIR__.'/web/slideshow', 'web/slideshow');