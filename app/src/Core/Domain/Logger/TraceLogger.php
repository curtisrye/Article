<?php

declare(strict_types=1);

namespace App\Core\Domain\Logger;

use App\Core\Domain\Model\Trace;

interface TraceLogger
{
    public function log(Trace $trace): void;
}