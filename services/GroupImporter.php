<?php

namespace services;

use dataTransformer\ImportToGroupDataTransformer;
use GroupRepository;

class GroupImporter
{
    public static function import(array $data): void
    {
        $persona = ImportToGroupDataTransformer::transform($data);
        if (!$persona) {
            return;
        }

        GroupRepository::save($persona);
    }

}
