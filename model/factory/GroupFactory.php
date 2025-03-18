<?php

namespace model\factory;

use model\Group;
use types\GroupType;

class GroupFactory
{
    public static function createFromPost(WP_Post $post): Group
    {
        return new Group(
            $post->ID, 
            get_post_meta( $post->ID, GroupType::getFieldDBId('name'), true),
            get_post_meta( $post->ID, GroupType::getFieldDBId('code'), true),
            get_post_meta( $post->ID, GroupType::getFieldDBId('active'), true) === "1",
            get_post_meta( $post->ID, GroupType::getFieldDBId('sequence'), true),
        );
    }
}
