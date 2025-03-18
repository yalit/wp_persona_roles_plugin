<?php

namespace model\factory;

use model\Role;
use types\RoleType;

class RoleFactory
{
    public static function createFromPost(WP_Post $post): Role
    {
        return new Role(
            $post->ID, 
            get_post_meta($post->ID, RoleType::getFieldDBId('name'), true),
            get_post_meta($post->ID, RoleType::getFieldDBId('code'), true),
            get_post_meta($post->ID, RoleType::getFieldDBId('description'), true),
            get_post_meta($post->ID, RoleType::getFieldDBId('sequence'), true)
        );
    }
}
