<?php

namespace services;

use dataTransformer\ImportToParishDataTransformer;
use ParishRepository;

class Parishimporter
{
    public static function import(array $data): void
    {
        $persona = ImportToParishDataTransformer::transform($data);
        if (!$persona) {
            return;
        }

        ParishRepository::save($persona);
    }

}
