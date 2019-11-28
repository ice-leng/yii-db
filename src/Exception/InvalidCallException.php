<?php

namespace Lengbin\YiiDb\Exception;

class InvalidCallException extends Exception
{
    public function getName()
    {
        return 'Invalid Call';
    }
}