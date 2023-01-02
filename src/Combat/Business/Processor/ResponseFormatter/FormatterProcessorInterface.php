<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\ResponseFormatter;

use GC\Combat\Transfer\FormatRequest;

interface FormatterProcessorInterface
{
    public function formatJson(FormatRequest $request): string;

    /**
     * @return array<string,mixed>
     */
    public function formatArray(FormatRequest $request): array;
}
