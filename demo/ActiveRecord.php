<?php

class ActiveRecord extends \Lengbin\YiiDb\ActiveRecord\ActiveRecord
{
    public static function getDb()
    {
        return new Query();
    }
}