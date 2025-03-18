<?php

namespace services;

use dataTransformer\ImportToPersonaDataTransformer;
use PersonaRepository;

class PersonaImporter
{
    public static function import(array $data): void
    {
        $persona = ImportToPersonaDataTransformer::transform($data);
        if (!$persona) {
            return;
        }

        PersonaRepository::save($persona);
    }

}
