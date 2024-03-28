<?php

namespace AlphaLabsUtilityPack\Helpers;

class CustomPostTypeFields {
    private $postType;
    private $metaBoxId;
    private $metaBoxTitle;
    private $fields;

    public function __construct($postType, $metaBoxId, $metaBoxTitle, $fields) {
        $this->postType = $postType;
        $this->metaBoxId = $metaBoxId;
        $this->metaBoxTitle = $metaBoxTitle;
        $this->fields = $fields;

        add_action('add_meta_boxes', array($this, 'addMetaBox'));
        add_action('save_post', array($this, 'saveMetaBoxData'));
    }

    public function addMetaBox() {
        add_meta_box(
            $this->metaBoxId,
            $this->metaBoxTitle,
            array($this, 'renderMetaBox'),
            $this->postType,
            'advanced', // Context
            'high'      // Priority
        );
    }

    public function renderMetaBox($post) {
        wp_nonce_field(basename(__FILE__), $this->metaBoxId . '_nonce');
        $storedMeta = get_post_meta($post->ID);

        echo '<div><table class="form-table alup-table" role="presentation"><tbody>';

        foreach ($this->fields as $field) {
            $value = isset($storedMeta[$field['id']]) ? esc_attr($storedMeta[$field['id']][0]) : '';
            echo '<tr><th scope="row"><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label></th>';
            echo '<td>' . $this->renderField($field, $value) . '</td></tr>';
        }

        echo '</tbody></table></div>';
    }

    private function renderField($field, $value) {
        if (!isset($field['type'])) {
            $field['type'] = 'text';
        }
        
        switch ($field['type']) {
            case 'textarea':
                return '<textarea name="' . esc_attr($field['id']) . '" id="' . esc_attr($field['id']) . '">' . esc_textarea($value) . '</textarea>';
    
            case 'number':
            case 'datetime':
                return '<input type="' . esc_attr($field['type']) . '" name="' . esc_attr($field['id']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($value) . '" />';
    
            default: // default to text
                return '<input type="text" name="' . esc_attr($field['id']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($value) . '" />';
        }
    }    

    public function saveMetaBoxData($post_id) {
        if (!isset($_POST[$this->metaBoxId . '_nonce'])) {
            return;
        }
        if (!wp_verify_nonce($_POST[$this->metaBoxId . '_nonce'], basename(__FILE__))) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        foreach ($this->fields as $field) {
            if (isset($_POST[$field['id']])) {
                update_post_meta($post_id, $field['id'], sanitize_text_field($_POST[$field['id']]));
            }
        }
    }
}
