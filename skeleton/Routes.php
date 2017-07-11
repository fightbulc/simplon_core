<?php

namespace {namespace};

use Simplon\Core\Utils\Routes;

/**
 * @package App\Components\Auth
 */
class {name}Routes extends Routes
{
    const PATTERN_FOO = '/bar';

    /**
     * @return string
     */
    public static function toBar(): string
    {
        return self::toString(
            self::render(self::PATTERN_FOO)
        );
    }
}