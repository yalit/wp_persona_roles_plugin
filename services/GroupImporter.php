<?php

namespace services;

use model\dataTransformer\ImportToGroupDataTransformer;
use repository\GroupRepository;

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
