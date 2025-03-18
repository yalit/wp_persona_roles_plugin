<?php

namespace pages;


use repository\GroupRepository;
use repository\ParishRepository;
use repository\RoleRepository;
use shortcode\Enum\ContentEnum;
use types\AffectationType;
use types\GroupType;
use types\ParishType;
use types\PersonaType;
use types\RoleType;

class AffectationHome
{
    public static function display(): void
    {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Roles et Affectations</h1>

            <div id="poststuff">
                <div class="postbox-container">
                    <div class="postbox">
                        <div class="postbox-header"><h2>Description du plugin</h2></div>
                        <div class="inside">
                            <?php static::description(); ?>
                        </div>
                    </div>
                </div>

                <div class="postbox-container">
                    <div class="postbox">
                        <div class="postbox-header"><h2>Génération de shortcode</h2></div>
                        <div class="inside">
                        <?php static::generator(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private static function description(): void
    {
        ?>
        <div>Ce plugin permet de gérer les rôles des différents intervenants dans les différentes paroisses de l'UP.</div>
        <div>Il permet d'un côté de gérer :
            <ul>
                <li>les <a href="/wp-admin/edit.php?post_type=<?php echo PersonaType::getPostType();?>">personnes</a> (leurs noms, prénoms et autres informations)</li> 
                <li>les différents <a href="/wp-admin/edit.php?post_type=<?php echo GroupType::getPostType();?>">groupes</a></li>
                <li>les différents <a href="/wp-admin/edit.php?post_type=<?php echo RoleType::getPostType();?>">rôles</a></li>
                <li>les différentes <a href="/wp-admin/edit.php?post_type=<?php echo ParishType::getPostType();?>">paroisses</a></li>
                <li>et enfin la combinaison de tout cela : les <a href="/wp-admin/edit.php?post_type=<?php echo AffectationType::getPostType();?>">affectations</a> => qui est la/le reponsable du groupe des lecteurs à Boignée, qui sont les membres de la chorale de Ligny, qui est l'organiste de Tongrinne...</li>
            </ul>
        </div>
        <div>Cette centralisation de l'information, permet de modifier l'information à un seul endroit et que cela puisse être changé automatique là où cela est listé dans le site via des shortcodes dans les pages et articles</div>
        <?php
    }

    private static function generator(): void
    {

        ?>
        <div>Le but ici est de pouvoir générer les différents shortcodes possibles afin de les utiliser dans les pages et articles</div>
        <form id="shortcode-generator-form">
            <h3>Format</h3>    
            <div class="form-group">
                <label>Format</label>
                <select id="select-format" name="format">
                    <option value="TABLEAU">Tableau</option>
                    <option value="CARTEVISITE">Carte de Visite</option>
                </select>
            </div>
            <h3>Contenu</h3>    
            <div class="form-line">
                <div class="form-group top">
                    <label>A afficher</label>
                    <select id="select-content" name="content" multiple size=<?php echo static::contentOptionSize(); ?>>
                        <?php static::contentOptions(); ?>
                    </select>
                </div>

                <div class="form-group top">
                    <label>Gras</label>
                    <select id="select-bold" name="bold" multiple size=<?php echo static::contentOptionSize(); ?>>
                        <?php static::contentOptions(); ?>
                    </select>
                </div>

                <div class="form-group top">
                    <label>Souligné</label>
                    <select id="select-underlined" name="underlined" multiple size=<?php echo static::contentOptionSize(); ?>>
                        <?php static::contentOptions(); ?>
                    </select>
                </div>

                <div class="form-group top">
                    <label>Italique</label>
                    <select id="select-italic" name="italic" multiple size=<?php echo static::contentOptionSize(); ?>>
                        <?php static::contentOptions(); ?>
                    </select>
                </div>
            </div>

            <h3>Filtres</h3>
            <div class="form-line">
                <div class="form-group">
                    <label>Paroisse</label>
                    <select id="select-filter-parish" name="parish">
                        <option value="">Aucune</option>
                        <?php static::parishOptions() ?>
                    </select>
                </div>

                
                <div class="form-group">
                    <label>Groupe</label>
                    <select id="select-filter-group" name="group">
                    <option value="">Aucun</option>
                        <?php static::groupOptions() ?>
                    </select>
                </div>

                
                <div class="form-group">
                    <label>Role</label>
                    <select id="select-filter-role" name="role">
                    <option value="">Aucun</option>
                        <?php static::roleOptions() ?>
                    </select>
                </div>
            </div>

            <h3>Ordre</h3>
            <div class="form-line">
                <div class="form-group">
                    <label>Order</label>
                    <select id="select-order" name="order">
                        <option value="">Aucun</option>
                        <?php static::contentOptions([ContentEnum::Order, ContentEnum::Address, ContentEnum::Image, ContentEnum::Phone]) ?>
                    </select>
                    <button type="button" id="add-order">Ajout ordre</button>
                </div>
            </div>
            
            <div class="form-line">
                <button type="submit">Générer</button>
                <div id="generated"></div>
            </div>

            <div class="form-line">
                <div id="shortcode-display"></div>
            </div>
        </form>
        <?php
    }

    /** @return array<ContentEnum> */
    private static function content($notToDisplay = [ContentEnum::Order]): array
    {
        $contentEnums = array_filter(ContentEnum::cases(), fn(ContentEnum $enum) => !in_array($enum, $notToDisplay));
        uasort($contentEnums, fn(ContentEnum $a, ContentEnum $b) => $a->display() === $b->display() ? 0 : ($a->display() < $b->display() ? -1 : 1));
        return array_values($contentEnums);
    }

    private static function contentOptionSize($notToDisplay = [ContentEnum::Order])
    {
        return count(static::content($notToDisplay));
    }

    private static function contentOptions($notToDisplay = [ContentEnum::Order])
    {
        foreach(static::content($notToDisplay) as $content) {
            ?>
            <option value="<?php echo $content->value; ?>"><?php echo $content->display(); ?></option>
            <?php
        }
    }

    private static function parishOptions()
    {
        $parishes = ParishRepository::findAll();
        foreach($parishes as $parish) {
            ?>
            <option value="<?php echo $parish->code; ?>"><?php echo $parish->name; ?></option>
            <?php
        }
    }

    private static function groupOptions()
    {
        $groups = GroupRepository::findAll();
        foreach($groups as $group) {
            ?>
            <option value="<?php echo $group->code; ?>"><?php echo $group->name; ?></option>
            <?php
        }
    }

    private static function roleOptions()
    {
        $roles = RoleRepository::findAll();
        foreach($roles as $role) {
            ?>
            <option value="<?php echo $role->code; ?>"><?php echo $role->name; ?></option>
            <?php
        }
    }
}
