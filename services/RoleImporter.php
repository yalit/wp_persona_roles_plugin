<?php

namespace services;

use model\dataTransformer\ImportToRoleDataTransformer;
use repository\RoleRepository;

class RoleImporter
{
    public static function import(array $data): void
    {
        $persona = ImportToRoleDataTransformer::transform($data);
        if (!$persona) {
            return;
        }

        RoleRepository::save($persona);
    }

}
