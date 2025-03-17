<?php

class AffectationRepository
{
    /** @return array<Affectation> */
    public static function findFiltered(
        ?string $parishCode = null, 
        ?string $groupCode = null,
        ?string $roleCode = null,
        ?string $orderBy = null,
    ): array {

        $args = [
            'post_type' => AffectationType::getPostType(),
            'posts_per_page' => -1,
        ];

        // handles the meta_fields selection
        $metaQueries = [];
        if ($parishCode) {
            static::addParishMetaQuery($metaQueries, $parishCode);
        }

        if ($groupCode) {
            static::addGroupMetaQuery($metaQueries, $groupCode);
        }

        if ($roleCode) {
            static::addRoleMetaQuery($metaQueries, $roleCode);
        }

        if (count($metaQueries) > 1) {
            $metaQueries = array_merge(['relation' => 'AND'], $metaQueries);
        }
        
        if (count($metaQueries) > 0) {
            $args['meta_query'] = $metaQueries;
        }

        $posts = (new WP_Query($args))->get_posts();
        $affectations = array_map(fn(WP_Post $post) => AffectationFactory::createFromPost($post), $posts);
        
        return static::order($affectations, $orderBy);
    }

    private static function addParishMetaQuery(&$metaqueries, $parishCode): void
    {
        $parish = ParishRepository::findFromCode($parishCode);

        if ($parish) {
            $metaqueries[] = [
                'key' => AffectationType::getFieldDBId('parish'),
                'value' => $parish->id,
                'compare' => '='
            ];
        }
    }

    private static function addGroupMetaQuery(&$metaqueries, $groupCode): void
    {
        $group = GroupRepository::findFromCode($groupCode);

        if ($group) {
            $metaqueries[] = [
                'key' => AffectationType::getFieldDBId('group'),
                'value' => $group->id,
                'compare' => '='
            ];
        }
    }

    private static function addRoleMetaQuery(&$metaqueries, $roleCode): void
    {
        $role = RoleRepository::findFromCode($roleCode);

        if ($role) {
            $metaqueries[] = [
                'key' => AffectationType::getFieldDBId('role'),
                'value' => $role->id,
                'compare' => '='
            ];
        }
    }

    /**
     * @param array<Affectation> $affectations
     * @return array<Affectation>
     */
    private static function order(array $affectations, $orderBy): array
    {
        /* @var array<ContentEnmu> $order */
        $order = array_map(fn($s) => ContentEnum::from($s), str_split($orderBy));
        uasort($affectations, function(Affectation $a, Affectation $b) use($order) {
            foreach($order as $enum) {
                if ($a->isEqual($b, $enum)) {
                    continue;
                }
                return $a->isLower($b, $enum) ? -1 : 1; 
            }
            
            return $a->isEqual($b, ContentEnum::Order) ? 0 : ($a->isLower($b, ContentEnum::Order) ? -1 : 1);
        });
        
        return array_values($affectations);
    }
}
