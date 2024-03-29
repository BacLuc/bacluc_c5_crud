<?php

namespace BaclucC5Crud\Lib;

interface ImmutableArrayAccess extends \ArrayAccess {
    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value);

    /**
     * @param mixed $offset
     *
     * @throws \BadMethodCallException
     */
    public function offsetUnset($offset);
}
