<?php
function create_countries_taxonomy() {
    register_taxonomy(
        'countries',
        'cities',
        [
            'label' => 'Countries',
            'hierarchical' => true,
            'rewrite' => ['slug' => 'countries'],
        ]
    );
}
add_action('init', 'create_countries_taxonomy');
