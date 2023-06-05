<?php

function formatDate(dateTime $dateTime, $dateStyle, $timeStyle): false|string
{
    $fmt = new IntlDateFormatter(
        "nl_NL",
        $dateStyle,
        $timeStyle,
        'GMT+00:00',
        IntlDateFormatter::GREGORIAN
    );

    return $fmt->format($dateTime);
}

function longDateNoTime(dateTime $dateTime): false|string
{
    return formatDate($dateTime, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
}

function longDateWithTime(dateTime $dateTime): false|string
{
    return formatDate($dateTime, IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
}

function noDateWithTime(dateTime $dateTime): false|string
{
    return formatDate($dateTime, IntlDateFormatter::NONE, IntlDateFormatter::SHORT);
}