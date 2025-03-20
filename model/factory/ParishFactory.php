<?php

namespace model\factory;

use model\Parish;
use types\ParishType;

use WP_Post;

class ParishFactory
{
    public static function createFromPost(WP_Post $post): Parish
    {
        return new Parish(
            $post->ID, 
            get_post_meta( $post->ID, ParishType::getFieldDBId('name'), true),
            get_post_meta( $post->ID, ParishType::getFieldDBId('code'), true),
            get_post_meta( $post->ID, ParishType::getFieldDBId('sequence'), true),
        );
    }
}
