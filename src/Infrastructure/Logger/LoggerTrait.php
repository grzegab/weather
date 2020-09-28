<?php

declare(strict_types=1);

namespace App\Infrastructure\Logger;

use Psr\Log\LoggerAwareTrait;

trait LoggerTrait
{
    private LoggerAwareTrait $loggerAware;

    /**
     * LoggerTrait constructor.
     * @param LoggerAwareTrait $loggerAware
     */
    public function __construct(LoggerAwareTrait $loggerAware)
    {
        $this->loggerAware = $loggerAware;
    }

    public function logEvent(string $eventText): void
    {
        //@TODO: make this trait working
    }
}