<?php

namespace Lengbin\YiiDb\Exception;

class InvalidArgumentException extends Exception
{
    public function getName()
    {
        return 'Invalid Argument';
    }
}