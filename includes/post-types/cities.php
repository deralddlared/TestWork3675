<?php
function create_cities_post_type() {
    register_post_type('cities', [
        'label' => 'Cities',
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'cities'],
    ]);
}
add_action('init', 'create_cities_post_type');
