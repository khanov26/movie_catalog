<?php

namespace app\traits;

trait ActiveRecordToStringTrait
{
    public function __toString()
    {
        return sprintf('table=%s, id=%d', static::tableName(), $this->id);
    }
}
