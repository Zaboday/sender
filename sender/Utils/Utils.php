<?php

namespace Sender\Utils;

class Utils
{

    /**
     * Gets from "Sender\Resources\FooResource" only "FooResource"
     *
     * @param $fullClassName
     *
     * @return string
     */
    public static function className($fullClassName): string
    {
        $arr = explode('\\', $fullClassName);

        return array_pop($arr);
    }
}