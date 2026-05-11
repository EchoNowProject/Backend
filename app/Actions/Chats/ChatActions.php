<?php

namespace App\Actions\Chats;

use Carbon\Carbon;

class ChatActions
{

    /**
     * Funcion para setear el tipo de mensaje con el que estamos tratando
     * @param mixed $message
     * @param mixed $files
     * @return int
     */
    public static function setTypeMessage(?string $message, ?array $files): int
    {
        if ($message != null && $files != null) {
            return 3;
        } elseif ($files != null) {
            return 2;
        } elseif ($message) {
            return 1;
        }

        return 0; // * El 0 no esta controlado
    }

    /**
     * Funcion para sacar la fecha en la que se envio el mensaje
     * @param mixed $created_at
     * @return string
     */
    public static function makeShippingTime($created_at)
    {
        $day = str(Carbon::parse($created_at)->day);
        $month = str(Carbon::parse($created_at)->month);
        $year = str(Carbon::parse($created_at)->year);
        $hour = str(Carbon::parse($created_at)->hour);
        $minutes = str(Carbon::parse($created_at)->minute);

        return $hour . ':' . $minutes . ' ' . $day . '-' . $month . '-' . $year;
    }
}
