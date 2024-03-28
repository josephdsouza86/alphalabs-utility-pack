<?php

namespace AlphaLabsUtilityPack\Helpers;

class CustomPostType {
    private $type;
    private $options;
    private $singular;
    private $plural;
    private $textdomain;

    public function __construct($type, $options = [], $singular = '', $plural = '', $textdomain = 'alpha-labs-utility') {
        $this->type = $type;
        $this->options = $options;
        $this->singular = !empty($singular) ? $singular : ucwords(str_replace('-', ' ', $this->type));
        $this->plural = !empty($plural) ? $plural : $this->singular . 's';
        $this->textdomain = $textdomain;

        if (!isset($this->options['labels'])) {
            $this->options['labels'] = $this->setDefaultLabels($this->singular, $this->plural, $this->textdomain);
        }

        add_action('init', array($this, 'registerPostType'));
    }

    private function setDefaultLabels($singular, $plural, $textdomain) {
        return array(
            'name'                  => _x($plural, `{$singular} General Name`, $textdomain),
            'singular_name'         => _x($singular, `{$singular} Singular Name`, $textdomain),
            'menu_name'             => __($plural, $textdomain),
            'name_admin_bar'        => __($singular, $textdomain),
            'archives'              => __($plural, $textdomain),
            'attributes'            => __($singular . ' Attributes', $textdomain),
            'parent_item_colon'     => __('Parent ' . $singular . ':', $textdomain),
            'all_items'             => __('All ' . $plural, $textdomain),
            'add_new_item'          => __('Add New ' . $singular, $textdomain),
            'add_new'               => __('Add New', $textdomain),
            'new_item'              => __('New ' . $singular, $textdomain),
            'edit_item'             => __('Edit ' . $singular, $textdomain),
            'update_item'           => __('Update ' . $singular, $textdomain),
            'view_item'             => __('View ' . $singular, $textdomain),
            'view_items'            => __('View ' . $plural, $textdomain),
            'search_items'          => __('Search ' . $plural, $textdomain),
            'not_found'             => __('No ' . strtolower($plural) . ' found', $textdomain),
            'not_found_in_trash'    => __('No ' . strtolower($plural) . ' found in Trash', $textdomain),
            'featured_image'        => __('Featured Image', $textdomain),
            'set_featured_image'    => __('Set featured image', $textdomain),
            'remove_featured_image' => __('Remove featured image', $textdomain),
            'use_featured_image'    => __('Use as featured image', $textdomain),
            'insert_into_item'      => __('Insert into ' . strtolower($singular), $textdomain),
            'uploaded_to_this_item' => __('Uploaded to this ' . strtolower($singular), $textdomain),
            'items_list'            => __($plural . ' list', $textdomain),
            'items_list_navigation' => __($plural . ' list navigation', $textdomain),
            'filter_items_list'     => __('Filter ' . strtolower($plural) . ' list', $textdomain),
        );
    }

    public function registerPostType() {
        register_post_type($this->type, $this->options);
    }
}
