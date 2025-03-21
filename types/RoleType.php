<?php

namespace types;

use WP_Query;

class RoleType extends AbstractType
{
    public static function getPostType(): string 
    { 
        return "persona_role";
    }

    public static function getName(): string 
    { 
        return "Rôle";
    }

    public static function getFields(): array 
    { 
        return [
            'name' => ['Nom', 'text'],
            'code' => ['Code', 'text'],
            'description' => ['Description', 'textarea'],
            'sequence' => ['Sequence', 'number'],
            'active' => ['Actif ?', 'boolean'],
        ];
    }

    public static function getPostTitle($postData): string 
    { 
        return $postData[static::getFieldId('name')];
    }

    public function addColumns($columns): array 
    { 
        unset($columns['date']);
        $columns['code'] = __('Code', 'persona_user_roles');
        $columns['sequence'] = __('Sequence', 'persona_user_roles');
        $columns['active'] = 'Actif ?';
        return $columns;
    }

    public function displayColumns($column_key, $post_id): void 
    {
        if ($column_key === 'active') {
            $value = get_post_meta( $post_id, static::getFieldDBId('active'), true );
            ?>
            <span><?php echo esc_attr($value ? "OUI" : "NON"); ?></span>
            <?php
        }
        if ($column_key === 'code') {
            $value = get_post_meta( $post_id, static::getFieldDBId('code'), true );
            ?>
            <span><?php echo esc_attr($value); ?></span>
            <?php
        }

        if ($column_key === 'sequence') {
            $value = get_post_meta( $post_id, static::getFieldDBId('sequence'), true );
            ?>
            <span><?php echo esc_attr($value); ?></span>
            <?php
        }
    }

    public function makeColumnsSortable($columns): array
    {
        $columns = parent::makeColumnsSortable($columns);

        $columns['active'] = 'active';
        $columns['code'] = 'code';
        $columns['sequence'] = 'sequence';
        return $columns;
    }

    public function sortColumns(WP_Query $query): void
    {
        $type = $query->get('post_type');
        $orderBy = $query->get('orderby');

        match ($type === static::getPostType() && in_array($orderBy, ['code', 'sequence', 'active'])) {
            true => $this->addOrderBy($query, static::getFieldDBId($orderBy)),
            false => null
        };
    }
}
