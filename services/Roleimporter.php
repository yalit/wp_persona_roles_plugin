<?php

namespace services;

use dataTransformer\ImportToRoleDataTransformer;
use RoleRepository;

class Roleimporter
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
