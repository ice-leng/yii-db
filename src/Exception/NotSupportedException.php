<?php

namespace Lengbin\YiiDb\Exception;

class NotSupportedException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Not Supported Exception';
    }
}