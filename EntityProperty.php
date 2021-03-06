<?php

namespace WebStream\Database;

/**
 * EntityPropertyクラス
 * @author Ryuichi Tanaka
 * @since 2017/05/29
 * @version 0.7
 */
trait EntityProperty
{
    /**
     * overload setter
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }

    /**
     * overload getter
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return null;
    }
}
