<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\ResponseFormatter;

use Psr\Container\ContainerInterface;

final class FormatterProcessorFactory
{
    public function __invoke(ContainerInterface $container): FormatterProcessorInterface
    {
        return new FormatterProcessor();
    }
}
