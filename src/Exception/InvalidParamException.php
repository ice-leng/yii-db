<?php

namespace Lengbin\YiiDb\Exception;

class InvalidParamException extends Exception
{
    public function getName()
    {
        return 'Invalid Param';
    }
}