<?php

namespace Lengbin\YiiDb\Exception;

class InvalidConfigException extends Exception
{
    public function getName()
    {
        return 'Invalid Config';
    }
}