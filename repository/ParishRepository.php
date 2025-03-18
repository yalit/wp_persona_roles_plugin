<?php

class ParishRepository extends AbstractRepository
{

    public static function find(string $parishID): ?Parish
    {
        $post = get_post($parishID);

        if (!$post) {
            return null;
        }

        return ParishFactory::createFromPost($post);
    }

    public static function findFromCode(string $parishCode): ?Parish
    {
        $args = [
            'post_type' => ParishType::getPostType(),
            'meta_query' => [
                [
                    'key' => ParishType::getFieldDBId('code'),
                    'value' => $parishCode,
                    'compare' => '='
                ]
            ]
        ];

        $posts = (new WP_Query($args))->get_posts();

        if (count($posts) !== 1) {
            return null;
        }

        $post = $posts[0];
        return ParishFactory::createFromPost($post);
    }

    /** @return array<Parish> */
    public static function findAll(): array
    {
        $args = [
            'post_type' => ParishType::getPostType(),
            'nb_per_page' => -1,
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_key' => ParishType::getFieldDBId('name')
        ];

        $posts = (new WP_Query($args))->get_posts();

        return array_map(fn(WP_Post $post) => ParishFactory::createFromPost($post), $posts);
    }

    public static function save(Parish $parish): void
    {
        $postId = "";
        if (!$parish->id) {
            $postId = static::createPost(sprintf("%s", $parish->name), ParishType::getPostType());
        }

        if ($parish->name) {
            update_post_meta($postId, ParishType::getFieldDBId('name'), $parish->name);
        }
        if ($parish->code) {
            update_post_meta($postId, ParishType::getFieldDBId('code'), $parish->code);
        }
        if ($parish->sequence && $parish->sequence !== "") {
            update_post_meta($postId, ParishType::getFieldDBId('sequence'), $parish->sequence);
        }
        if($parish->legacyId && $parish->legacyId !== "") {
            update_post_meta($postId, ParishType::getFieldDBId(AffectationImporter::LEGACY_ID_FIELD_NAME), $parish->legacyId);
        }
    }
}
