<?php

namespace Haikara\Verifier;

use Haikara\Verifier\Types\CharString;
use Haikara\Verifier\Types\Double;
use Haikara\Verifier\Types\File;
use Haikara\Verifier\Types\Integer;
use Haikara\Verifier\Types\Number;

class Rules
{
    /**
     * @return Integer
     */
    public static function integer(): RuleInterface
    {
        return new Integer();
    }

    /**
     * @return Double
     */
    public static function float(): RuleInterface
    {
        return new Double();
    }

    /**
     * @return Number
     */
    public static function number(): RuleInterface
    {
        return new Number();
    }

    /**
     * @return CharString
     */
    public static function string(): RuleInterface
    {
        return new CharString();
    }

    /**
     * @return File
     */
    public static function file(): RuleInterface
    {
        return new File();
    }
}
