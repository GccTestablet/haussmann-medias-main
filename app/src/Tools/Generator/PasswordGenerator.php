<?php

declare(strict_types=1);

namespace App\Tools\Generator;

class PasswordGenerator
{
    private const LOWER_CASES = 'abcdefghijklmnpqrstuvwxyz';
    private const UPPER_CASES = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
    private const DIGITS = '123456789';

    public static function generate(int $length, bool $lowerCases = true, bool $upperCases = true, bool $digits = true): string
    {
        $password = '';
        $validChars = [];
        if ($lowerCases) {
            $validChars[] = self::LOWER_CASES;
        }

        if ($upperCases) {
            $validChars[] = self::UPPER_CASES;
        }

        if ($digits) {
            $validChars[] = self::DIGITS;
        }

        $validChars = \implode('', $validChars);
        for ($i = 0; $i < $length; ++$i) {
            // Sélection aléatoire d'un caractère autorisé
            $password .= $validChars[\random_int(0, \strlen($validChars) - 1)];
        }

        $isValidPassword = ($upperCases && !\preg_match('/[A-Z]/', $password))
            || ($lowerCases && !\preg_match('/[a-z]/', $password))
            || ($digits && !\preg_match('/\d/', $password));

        if ($isValidPassword) {
            return self::generate($length, $lowerCases, $upperCases, $digits);
        }

        return $password;
    }
}
