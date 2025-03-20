<?php

namespace types;

use repository\GroupRepository;
use repository\ParishRepository;
use repository\RoleRepository;
use WP_Query;

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

    public function makeColumnsSortable($columns): array
    {
        $columns = parent::makeColumnsSortable($columns);
        $columns['role'] = 'role';
        $columns['group'] = 'group';
        $columns['parish'] = 'parish';
        return $columns;
    }

    public function sortColumns(WP_Query $query): void
    {
        $type = $query->get('post_type');
        $orderBy = $query->get('orderby');
        $order = $query->get('order');

        if (!($type === static::getPostType() && in_array($orderBy, ['role', 'group', 'parish']))) {
            return;
        }

        add_filter( 'posts_groupby', function( $groupby ) use($orderBy) {
            return '';
        } );

        add_filter('posts_join', function($joins) use($orderBy) {
            global $wpdb;
            $joins .= " LEFT JOIN $wpdb->postmeta AS referenced_meta ON ($wpdb->posts.ID = referenced_meta.post_id AND referenced_meta.meta_key = '".static::getFieldDBId($orderBy)."')";
            $joins .= " LEFT JOIN $wpdb->posts AS referenced_posts ON referenced_meta.meta_value = referenced_posts.ID";

            $metaKey = match($orderBy) {
                'role' => RoleType::getFieldDBId('name'),
                'group' => GroupType::getFieldDBId('name'),
                'parish' => ParishType::getFieldDBId('name'),
            };

            $joins .= " LEFT JOIN $wpdb->postmeta AS referenced_value_meta ON (referenced_posts.ID = referenced_value_meta.post_id AND referenced_value_meta.meta_key = '" . $metaKey . "')";

            return $joins;
        });

        add_filter('posts_orderby', function($orderby) use($order) {
            return "referenced_value_meta.meta_value " . $order;
        });
    }
}
