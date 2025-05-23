<?php
namespace app\helpers;

class Icon
{
    public static function svg($name): string
    {
        return str_replace('<svg', '<svg class="icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" ', static::$data[$name] ?? '');
    }

    private static array $data = [
        'plus' => '<svg viewBox="0 0 48 48">
            <path d="M16 6H8C6.89543 6 6 6.89543 6 8V16" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 42H8C6.89543 42 6 41.1046 6 40V32" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M32 42H40C41.1046 42 42 41.1046 42 40V32" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M32 6H40C41.1046 6 42 6.89543 42 8V16" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M32 24L16 24" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M24 32L24 16" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>'
    ];
}

