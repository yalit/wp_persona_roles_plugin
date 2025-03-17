<?php

class GroupType extends AbstractType
{

    public static function getPostType(): string 
    { 
        return 'persona_group';
    }

    public static function getName(): string 
    { 
        return "Groupe";
    }

    public static function getFields(): array 
    { 
        return [
            'name' => ['Nom', 'text'],
            'code' => ['Code', 'text'],
            'active' => ['Actif ?', 'boolean'],
        ];
    }

    public static function getPostTitle($postData): string 
    { 
        return sanitize_text_field($postData[static::getFieldId('name')]);
    }

    public function addColumns($columns): array {
        unset($columns['date']);
        return array_merge($columns, [
            'active' => __('Actif ?', 'persona-user-roles'),
            'code' => __('CODE', 'persona-user-roles'),
        ]);
    }

    public function displayColumns ($column_key, $post_id): void {
        if ($column_key == 'code') {
            $code = get_post_meta($post_id, static::getFieldDBId('code'), true);
            ?>
            <span><?php echo esc_attr($code); ?></span>
        <?php
        }

        if ($column_key == 'active') {
            $active = get_post_meta($post_id, static::getFieldDBId('active'), true);
            ?>
            <span><?php echo (!empty($active) ? "OUI" : "NON"); ?></span>
        <?php
        }
    }
}