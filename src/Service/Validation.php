<?php
namespace App\Service;

use DateTime;


class Validation
{
    public const REQUEST_ID = '/^[A-Za-z0-9]+$/';  
    public const NAME_RE = '/^[а-яё -]+$/iu';
    public const FULL_NAME_RE = '/^[а-яё. -]+$/iu';
 
    public static function checkArray(array $array, array $constraints): bool
    {

        foreach ($constraints as $key => $checker) {

            if (!array_key_exists($key, $array) || !$checker($array[$key])) {
                
                return false;
            }
        }

        return empty(array_diff_key($constraints, $array));
    }

    public static function isName($value): bool
    {
        return is_string($value) &&
            preg_match(self::NAME_RE, $value);
    }

    public static function isFullName($value): bool
    {
        return is_string($value) &&
            preg_match(self::FULL_NAME_RE, $value);
    }
 
    public static function isPastDate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $date = self::parseDate($value);
        if (null === $date) {
            return false;
        }

        $tomorrow_date = new DateTime('+1 days 00:00:00');

        return $date < $tomorrow_date;
    }

    public static function isContactDate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $date = self::parseDate($value);
        if (null === $date) {
            return false;
        }

        $tomorrow_date = new DateTime('+1 days 00:00:00');
        $min_contact_date = new DateTime('-14 days 00:00:00');

        return $date < $tomorrow_date && $date >= $min_contact_date;
    }


    public static function isRequestId($value): bool
    {
        return is_string($value) &&
            preg_match(self::REQUEST_ID, $value);
    }

    public static function isInn($value): bool
    {
        return is_string($value) &&
            ctype_digit($value);
    }

     public static function isOms($value): bool
    {
        return is_string($value) &&
            ctype_digit($value);
    }

    public static function isSnils($value): bool
    {
        return is_string($value) &&
            ctype_digit($value);
    }

    public static function parseDate(string $value): ?DateTime
    {
        $date = DateTime::createFromFormat('d.m.Y', $value);
        if (null === $date) {
            return null;
        }

        $date->setTime(0, 0);

        return $date;
    }

}
