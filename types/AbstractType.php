<?php

namespace types;

abstract class AbstractType
{
    public static function init(): void
    {
        new static();
    }

    public function __construct() 
    {
        $this->initType();
        
        add_action( 'add_meta_boxes', [$this, 'addMetaBox']);
        add_action( 'save_post_'.static::getPostType(), [ $this, 'saveData']);
        add_filter( 'wp_insert_post_data', [ $this, 'updateTitleFromMeta' ], 10, 3);

        add_filter( 'manage_'.static::getPostType().'_posts_columns', [$this, 'addColumns']);
        add_action( 'manage_'.static::getPostType().'_posts_custom_column', [$this, 'displayColumns'], 10, 2);
    }

    abstract public static function getPostType(): string;
    abstract public static function getName(): string; 
    abstract public static function getFields(): array;
    abstract public static function getPostTitle($postData): string;

    abstract public function addColumns($columns): array;
    abstract public function displayColumns($column_key, $post_id): void;

    public static function getFieldId($fieldKey)
    {
        return sprintf("%s_%s", static::getPostType(), $fieldKey);
    }

    public static function getFieldDBId($fieldKey)
    {
        return sprintf("_%s",static::getFieldId($fieldKey));
    }

    protected function initType(): void
    {
        $args = array(
            'labels' => array(
                'name'          => static::getName(),
                'singular_name' => static::getName(),
                'menu_name'     => static::getName()."s",
                'add_new'       => 'Add New '.static::getName(),
                'add_new_item'  => 'Add New '.static::getName(),
                'new_item'      => 'New '.static::getName(),
                'edit_item'     => 'Edit '.static::getName(),
                'view_item'     => 'View '.static::getName(),
                'all_items'     => 'All '.static::getName(),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array( 'title' ),
            'show_in_menu' => '', //removes it from any menu and menu is built separately see: persona_menu.php
        );
 
        register_post_type( static::getPostType(), $args );
    }

    public function addMetaBox() 
    {
        add_meta_box(
            static::getPostType().'_details', static::getName().' information',
            [$this, 'renderMetaBox'],
            static::getPostType(),
            'normal',
            'high'
        );
    }

    public function renderMetaBox( $post )
    {
        // Add nonce for security
        wp_nonce_field(static::getPostType().'data_save', static::getPostType().'data_nonce');
        
        // Get saved values
        $values = [];

        foreach(static::getFields() as $key => $details) {
            $values[$key] = get_post_meta($post->ID, $this->getFieldDBId($key), true);
        }
        
        ?>
        <div class="<?php echo static::getPostType();?>_details">
            <?php
                $fields = static::getFields();
                foreach($values as $key => $value) {
                    $id = static::getFieldId($key);
                    if ($fields[$key][1] === "text" || $fields[$key][1] === "email") {
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <input type="<?php echo $fields[$key][1]; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo esc_attr($value); ?>" class="widefat" />
                        </p>
                    <?php
                    }

                    if ($fields[$key][1] === "number") {
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $value ? esc_attr($value) : "1"; ?>" class="widefat" />
                        </p>
                    <?php
                    }
                    
                    if ($fields[$key][1] === "textarea") {
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <textarea id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="widefat" rows="10"><?php echo esc_textarea($value); ?></textarea>
                        </p>
                    <?php
                    }

                    if ($fields[$key][1] === "choice") {
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                                <?php
                                    foreach($fields[$key][2] as $option) {
                                        ?>
                                        <option value="<?php echo $option; ?>" <?php if ($value === $option) echo "selected"; ?>><?php echo $option; ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </p>
                    <?php
                    }

                    if ($fields[$key][1] === "boolean") {
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $id; ?>" <?php if ($value && $value !== "") {echo "checked";} ?> />
                        </p>
                    <?php
                    }

                    if ($fields[$key][1] === "file") {
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <input type="file" id="<?php echo $id; ?>" name="<?php echo $id; ?>" />
                            <?php
                                if (!empty($value)) {
                                    ?>
                                    <img src="<?php echo esc_attr($value)?>"/>
                                    <?php
                                }
                            ?>
                        </p>
                    <?php
                    }

                    if ($fields[$key][1] === "relation") {
                        $args = ['post_type' => $fields[$key][2]::getPostType(), 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'];
                        $posts = get_posts($args);
                    ?>
                        <p>
                            <label for="<?php echo $id; ?>"><strong><?php _e(sprintf('%s : ', $fields[$key][0]), 'user_roles'); ?></strong></label>
                            <select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                                <option value=""></option>
                                <?php    
                                    foreach($posts as $option) {
                                        ?>
                                        <option value="<?php echo $option->ID; ?>" <?php if (intval($value) === $option->ID) echo "selected"; ?>><?php echo $option->post_title; ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </p>
                    <?php
                    }
                }
            ?>
        </div>
        <?php
    }

    public function saveData($post_id) {
        // Check if nonce is set
        if (!isset($_POST[static::getPostType().'data_nonce'])) {
            return $post_id;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST[static::getPostType().'data_nonce'], static::getPostType().'data_save')) {
            return $post_id;
        }
        
        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        // Check post type
        if (static::getPostType() !== $_POST['post_type']) {
            return $post_id;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        
        
        // Sanitize and save data
        foreach(static::getFields() as $key => $details) {
            $postKey = static::getFieldId($key);
            if (array_key_exists($postKey, $_POST)) {
                if ($details[1] === 'email') {
                    update_post_meta($post_id, static::getFieldDBId($key), sanitize_email($_POST[$postKey]));
                } else if ($details[1] === 'boolean') {
                    update_post_meta($post_id, static::getFieldDBId($key), $_POST[$postKey] === 'on');
                } else if ($details[1] === 'textarea') {
                    update_post_meta($post_id, static::getFieldDBId($key), sanitize_textarea_field($_POST[$postKey]));
                } else { // Default saver
                    update_post_meta($post_id, static::getFieldDBId($key), sanitize_text_field($_POST[$postKey]));
                }
            } else if (array_key_exists($postKey, $_FILES)) {
                if ($details[1] === 'file') { 
                    if (!empty($_FILES[$postKey]['name'])) {
                        $upload = wp_handle_upload($_FILES[$postKey], array('test_form' => false));
                        if (isset($upload['url'])) {
                            update_post_meta($post_id, '_'.$postKey, $upload['url']);
                        }
                    }
                }
            }
            else {
                if ($details[1] === 'boolean') {
                    $value = get_post_meta($post_id, static::getFieldDBId($key), true);
                    if (!empty($value)) {
                        delete_post_meta($post_id, static::getFieldDBId($key));
                    }
                }
            }
        }      
        
    }

    public function updateTitleFromMeta($data, $postarr)
    {
        if (array_key_exists('post_type', $data) && $data['post_type'] === static::getPostType() && $postarr['post_title'] === "" && $postarr['action'] === 'editpost') {
            $data['post_title'] = static::getPostTitle($postarr);
        }

        return $data;
    }
}
