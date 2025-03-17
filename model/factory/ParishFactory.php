<?php

class ParishFactory
{
    public static function createFromPost(WP_Post $post): Parish
    {
        return new Parish(
            $post->ID, 
            get_post_meta( $post->ID, ParishType::getFieldDBId('name'), true),
            get_post_meta( $post->ID, ParishType::getFieldDBId('code'), true)
        );
    }
}