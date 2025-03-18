<?php

namespace model\factory;

use model\Affectation;
use repository\GroupRepository;
use repository\ParishRepository;
use repository\PersonaRepository;
use repository\RoleRepository;
use types\AffectationType;

class AffectationFactory
{
    public static function createFromPost(WP_Post $post): Affectation
    {
        return new Affectation(
            $post->ID, 
            PersonaRepository::find(get_post_meta( $post->ID, AffectationType::getFieldDBId('persona'), true)),
            ParishRepository::find(get_post_meta( $post->ID, AffectationType::getFieldDBId('parish'), true)),
            GroupRepository::find(get_post_meta( $post->ID, AffectationType::getFieldDBId('group'), true)),
            RoleRepository::find(get_post_meta( $post->ID, AffectationType::getFieldDBId('role'), true)),
            get_post_meta( $post->ID, AffectationType::getFieldDBId('order'), true)
        );
    }
}
