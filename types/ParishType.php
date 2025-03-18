<?php

class ParishType extends AbstractType
{
    public static function getPostType(): string 
    { 
        return "persona_parish";
    }

    public static function getName(): string 
    { 
        return "Paroisse";
    }

    public static function getFields(): array 
    { 
        return [
            'name' => ['Nom', 'text'],
            'code' => ['Code', 'text'],
            'sequence' => ['Sequence', 'number'],
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
        return $columns;
    }

    public function displayColumns($column_key, $post_id): void 
    { 
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
    
}
