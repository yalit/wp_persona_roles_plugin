<?php

namespace services;

use dataTransformer\ImportToAffectationDataTransformer;
use AffectationRepository;

class AffectationImporter
{
    public static function import(array $data): void
    {
        $persona = ImportToAffectationDataTransformer::transform($data);
        if (!$persona) {
            return;
        }

        AffectationRepository::save($persona);
    }

}
