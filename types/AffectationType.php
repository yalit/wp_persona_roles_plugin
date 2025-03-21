<?php

namespace types;

use repository\GroupRepository;
use repository\ParishRepository;
use repository\PersonaRepository;
use repository\RoleRepository;
use WP_Query;

class AffectationType extends AbstractType
{
    public function __construct()
    {
        parent::__construct();
        add_action('restrict_manage_posts', [$this, 'addFilters']);
        add_filter('pre_get_posts', [$this, 'filterPosts']);
    }

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
        $columns['persona'] = __('Personne', 'persona_user_roles');
        $columns['group'] = __('Groupe', 'persona_user_roles');
        $columns['parish'] = __('Paroisse', 'persona_user_roles');
        $columns['role'] = __('Role', 'persona_user_roles');

        return $columns;
    }

    public function displayColumns($column_key, $post_id): void
    {
        if ($column_key === 'persona') {
            $personaId = get_post_meta($post_id, static::getFieldDBId('persona'), true);
            $persona = PersonaRepository::find($personaId);
            ?>
            <span><?php echo sprintf("%s %s", esc_attr($persona->name), esc_attr($persona->surname)); ?></span>
            <?php
        }

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
        $columns['persona'] = 'persona';
        $columns['role'] = 'role';
        $columns['group'] = 'group';
        $columns['parish'] = 'parish';
        return $columns;
    }

    public function sortColumns(WP_Query $query): void
    {
        $isApplied = function (WP_Query $currentQuery): bool {
            return $currentQuery->get('post_type') === static::getPostType() && in_array($currentQuery->get('orderby'), ['role', 'group', 'parish', 'persona']);
        };

        if (!$isApplied($query)) {
            return;
        }

        add_filter( 'posts_groupby', function( $groupby, &$query ) use ($isApplied) {
            if (!$isApplied($query)) {
                return $groupby;
            }
            return '';
        }, 10, 2 );

        add_filter('posts_join', function($joins, &$query) use($isApplied) {
            if (!$isApplied($query)) {
                return $joins;
            }

            $orderBy = $query->get('orderby');
            global $wpdb;
            $joins .= " LEFT JOIN $wpdb->postmeta AS referenced_meta ON ($wpdb->posts.ID = referenced_meta.post_id AND referenced_meta.meta_key = '".static::getFieldDBId($orderBy)."')";
            $joins .= " LEFT JOIN $wpdb->posts AS referenced_posts ON referenced_meta.meta_value = referenced_posts.ID";

            $metaKey = match($orderBy) {
                'persona' => PersonaType::getFieldDBId('name'),
                'role' => RoleType::getFieldDBId('name'),
                'group' => GroupType::getFieldDBId('name'),
                'parish' => ParishType::getFieldDBId('name'),
                default => null
            };

            if ($metaKey) {
                $joins .= " LEFT JOIN $wpdb->postmeta AS referenced_value_meta ON (referenced_posts.ID = referenced_value_meta.post_id AND referenced_value_meta.meta_key = '" . $metaKey . "')";
            }

            return $joins;
        }, 10, 2);

        add_filter('posts_orderby', function($orderby, &$query) use ($isApplied) {
            if (!$isApplied($query)) {
                return $orderby;
            }
            $order = $query->get('order');
            return "referenced_value_meta.meta_value " . $order;
        }, 10, 2);
    }

    public function addFilters($postType): void
    {
        if ($postType !== static::getPostType()) { return; }

        $roles = RoleRepository::findAll();
        $parishes = ParishRepository::findAll();
        $groups = GroupRepository::findAll();

        $selectedGroup = (array_key_exists(static::getPostType().'_filter_group', $_GET) && $_GET[static::getPostType().'_filter_group'] !== '') ? sanitize_text_field($_GET[static::getPostType().'_filter_group']) : '';
        $selectedParish = (array_key_exists(static::getPostType().'_filter_parish', $_GET) && $_GET[static::getPostType().'_filter_parish'] !== '') ? sanitize_text_field($_GET[static::getPostType().'_filter_parish']) : '';
        $selectedRole = (array_key_exists(static::getPostType().'_filter_role', $_GET) && $_GET[static::getPostType().'_filter_role'] !== '') ? sanitize_text_field($_GET[static::getPostType().'_filter_role']) : '';

        $additionalFilters = '<select name="'.static::getPostType().'_filter_group">';
        $additionalFilters .= '<option value="">Tous les Groupes</option>';
        foreach ($groups as $group) {
            $additionalFilters .= '<option value="'.$group->id.'" '.($group->id === $selectedGroup? "selected" : "").'>'.$group->name.'</option>';
        }
        $additionalFilters .= '</select>';

        $additionalFilters .= '<select name="'.static::getPostType().'_filter_parish">';
        $additionalFilters .= '<option value="">Toutes les paroisses</option>';
        foreach ($parishes as $parish) {
            $additionalFilters .= '<option value="'.$parish->id.'" '.($parish->id === $selectedParish ? "selected" : "").'>'.$parish->name.'</option>';
        }
        $additionalFilters .= '</select>';

        $additionalFilters .= '<select name="'.static::getPostType().'_filter_role">';
        $additionalFilters .= '<option value="">Tous les rôles</option>';
        foreach ($roles as $role) {
            $additionalFilters .= '<option value="'.$role->id.'" '.($role->id === $selectedRole ? "selected" : "").'>'.$role->name.'</option>';
        }
        $additionalFilters .= '</select>';

        echo $additionalFilters;
    }

    public function filterPosts(&$query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== static::getPostType()) {
            return;
        }

        $metaQuery = [];

        if ((array_key_exists(static::getPostType().'_filter_group', $_GET) && $_GET[static::getPostType().'_filter_group'] !== '')) {
            $groupId = sanitize_text_field($_GET[static::getPostType().'_filter_group']);
            $metaQuery[] = [
                'key' => static::getFieldDBId('group'),
                'value' => $groupId,
                'compare' => '='
            ];
        }

        if ((array_key_exists(static::getPostType().'_filter_parish', $_GET) && $_GET[static::getPostType().'_filter_parish'] !== '')) {
            $parishId = sanitize_text_field($_GET[static::getPostType().'_filter_parish']);
            $metaQuery[] = [
                'key' => static::getFieldDBId('parish'),
                'value' => $parishId,
                'compare' => '='
            ];
        }

        if ((array_key_exists(static::getPostType().'_filter_role', $_GET) && $_GET[static::getPostType().'_filter_role'] !== '')) {
            $roleId = sanitize_text_field($_GET[static::getPostType().'_filter_role']);
            $metaQuery[] = [
                'key' => static::getFieldDBId('role'),
                'value' => $roleId,
                'compare' => '='
            ];
        }

        if(count($metaQuery) > 0) {
            $query->set('meta_query', $metaQuery);
        }
    }
}
