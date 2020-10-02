<?php

namespace App\Helpers;

use Parsedown;

class StringHelper
{
    /**
     * Return the Markdown text as a parsed string.
     * Also apply a safe mode to get rid of dangerous html.
     *
     * @param string $content
     *
     * @return string
     */
    public static function parse(string $content): string
    {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);

        return $parsedown->text($content);
    }
}
