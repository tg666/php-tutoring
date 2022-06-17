<?php

declare(strict_types=1);

use Tracy\Debugger;

require __DIR__ . '/../vendor/autoload.php';

Debugger::enable(Debugger::DEVELOPMENT);
