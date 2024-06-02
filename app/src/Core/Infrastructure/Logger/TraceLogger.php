<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Logger;

use App\Core\Domain\Logger\TraceLogger as TraceLoggerInterface;
use App\Core\Domain\Model\Trace;
use Doctrine\DBAL\Connection;

class TraceLogger implements TraceLoggerInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public function log(Trace $trace): void
    {
        $this->connection->insert(
            'trace_log',
            [
                'modelName' => $trace->modelName(),
                'oldFields' => json_encode($trace->oldFields()),
                'newFields' => json_encode($trace->newFields()),
                'updatedAt' => $trace->updatedAt()->format(DATE_ATOM),
                'updatedBy' => $trace->updatedBy()
            ]
        );
    }
}