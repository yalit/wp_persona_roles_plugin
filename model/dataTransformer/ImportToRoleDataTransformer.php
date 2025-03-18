<?php

namespace dataTransformer;

use Role;

class ImportToRoleDataTransformer extends GenericDataTransformer
{
    public static function transform(array $data): ?Role
    {
        return new Role(
            null,
            sanitize_text_field(static::getProperty($data, 'nom_role', "")),
            sanitize_text_field(static::getProperty($data, 'code_role', "")),
            sanitize_text_field(static::getProperty($data, 'description_role', "")),
            sanitize_text_field(static::getProperty($data, 'seq_role', "")),
            sanitize_text_field(static::getProperty($data, 'id', "")),
        );
    }
}
