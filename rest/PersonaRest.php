<?php

namespace rest;

class PersonaRest
{
    public const BASE_URL = 'persona-roles';

    public static function registerRoutes()
    {
        register_rest_route(self::BASE_URL, '/shortcode/display', [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [AffectationShortcodeRestDisplay::class, 'provideShortcodeDisplay'],
        ]);
    }
}
