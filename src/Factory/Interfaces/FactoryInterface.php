<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Foundation\Factory\Interfaces;

interface FactoryInterface
{
    public static function getTemplateNamespace(): string;

    public static function getExpectedClassType(): ?string;
}
