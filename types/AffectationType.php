<?php

class AffectationType extends AbstractType
{
    public static function getPostType(): string 
    { 
        return "persona_affectation";
    }

    public static function getName(): string 
    { 
        return "Affectation";
    }

    public static function getFields(): array 
    { 
        return [
            'persona' => ['Personne', 'relation', PersonaType::class],
            'group' => ['Groupe', 'relation', GroupType::class],
            'role' => ['Rôle', 'relation', RoleType::class],
            'parish' => ['Paroisse', 'relation', ParishType::class],
            'order' => ['Séquence', "number"]
        ];
    }

    public static function getPostTitle($postData): string 
    { 
        $persona = get_post($postData[static::getFieldId('persona')]);
        $group = get_post($postData[static::getFieldId('group')]);
        return sprintf("%s-%s", $group->post_title, $persona->post_title);
    }

    public function addColumns($columns): array
    { 
        unset($columns['date']);
        $columns['group'] = __('Groupe', 'persona_user_roles');
        $columns['parish'] = __('Paroisse', 'persona_user_roles');
        $columns['role'] = __('Role', 'persona_user_roles');

        return $columns;
    }

    public function displayColumns($column_key, $post_id): void
    { 
        if ($column_key === 'role') {
            $roleId = get_post_meta($post_id, static::getFieldDBId('role'), true);
            $role = RoleRepository::find($roleId);
            ?>
            <span><?php echo esc_attr($role->name); ?></span>
            <?php
        }
        
        if ($column_key === 'group') {
            $groupID = get_post_meta($post_id, static::getFieldDBId('group'), true);
            $group = GroupRepository::find($groupID);
            ?>
            <span><?php echo esc_attr($group->name); ?></span>
            <?php
        }

        if ($column_key === 'parish') {
            $parishID = get_post_meta($post_id, static::getFieldDBId('parish'), true);
            $parish = ParishRepository::find($parishID);
            ?>
            <span><?php echo esc_attr($parish->name); ?></span>
            <?php
        }
    }
}
