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

if (! function_exists('tempo_relativo')) {
    /**
     * Retorna texto relativo em português (ex.: "3 minutos atrás", "agora").
     */
    function tempo_relativo(mixed $data, ?Time $referencia = null): string
    {
        $criado = app_time($data);
        if ($criado === null) {
            return '';
        }

        $hoje = $referencia ?? Time::now();
        $diferenca = $criado->difference($hoje);

        if ($diferenca->minutes < 1) {
            return 'agora';
        }
        if ($diferenca->hours < 1) {
            $s = ($diferenca->minutes > 1) ? 's' : '';
            return $diferenca->minutes . ' minuto' . $s . ' atrás';
        }
        if ($diferenca->days < 1) {
            $s = ($diferenca->hours > 1) ? 's' : '';
            return $diferenca->hours . ' hora' . $s . ' atrás';
        }
        if ($diferenca->weeks < 1) {
            $s = ($diferenca->days > 1) ? 's' : '';
            return $diferenca->days . ' dia' . $s . ' atrás';
        }
        if ($diferenca->months < 1) {
            $s = ($diferenca->weeks > 1) ? 's' : '';
            return $diferenca->weeks . ' semana' . $s . ' atrás';
        }
        if ($diferenca->years < 1) {
            $s = ($diferenca->months > 1) ? 'es' : '';
            return $diferenca->months . ' mês' . $s . ' atrás';
        }

        $s = ($diferenca->years > 1) ? 's' : '';
        return $diferenca->years . ' ano' . $s . ' atrás';
    }
}
