<?php

use CodeIgniter\I18n\Time;

if (! function_exists('app_time')) {
    /**
     * Normaliza valores de data (string, Time ou DateTimeInterface) para Time.
     * Necessário após CI 4.5+, que retorna objetos Time nos models com dateFormat datetime.
     */
    function app_time(mixed $value, ?string $timezone = null): ?Time
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Time) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return Time::createFromInstance($value);
        }

        return Time::parse((string) $value, $timezone);
    }
}
