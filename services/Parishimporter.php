<?php

namespace services;

use model\dataTransformer\ImportToParishDataTransformer;
use repository\ParishRepository;

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
