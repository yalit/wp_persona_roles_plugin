<?php

namespace dataTransformer;


use Group;

class ImportToGroupDataTransformer extends GenericDataTransformer
{
    public static function transform(array $data): ?Group
    {
        return new Group(
            null,
            sanitize_text_field(static::getProperty($data, 'nom_groupe', "")),
            sanitize_text_field(static::getProperty($data, 'code_groupe', "")),
            sanitize_text_field(static::getProperty($data, 'actif', "")) === "1",
            sanitize_text_field(static::getProperty($data, 'seq_groupe', "")),
            sanitize_text_field(static::getProperty($data, 'id', "")),
        );
    }
}
