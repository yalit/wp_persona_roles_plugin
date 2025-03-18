<?php

namespace services;

use model\dataTransformer\ImportToPersonaDataTransformer;
use repository\PersonaRepository;

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
