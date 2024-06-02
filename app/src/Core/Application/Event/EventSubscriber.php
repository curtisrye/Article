<?php

declare(strict_types=1);

namespace App\Core\Application\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface EventSubscriber extends EventSubscriberInterface
{
    public static function getSubscribedEvents(): array;
}