<?php
declare(strict_types=1);

namespace Lengbin\YiiDb\Exception;

class InvalidArgumentException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Argument';
    }
}

