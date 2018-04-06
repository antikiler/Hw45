<?php

namespace AppBundle\Lib\Enumeration;

class IssueStatus
{
    const JUST_CREATED = 1;
    const IN_PROGRESS = 2;
    const DONE = 3;

    public static function getALL()
    {
        return [
            self::JUST_CREATED => "Новая",
            self::IN_PROGRESS => "Исполняется",
            self::DONE => "Выполненная"
        ];
    }

}