<?php

declare(strict_types=1);

namespace App\Core\Domain\Event;

use App\Core\Application\Event\Event;

interface EventDispatcher
{
    public function dispatch(Event $event): void;
}