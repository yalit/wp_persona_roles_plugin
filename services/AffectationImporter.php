<?php

namespace services;

use model\dataTransformer\ImportToAffectationDataTransformer;
use repository\AffectationRepository;

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
