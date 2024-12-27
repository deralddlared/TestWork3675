<?php
function storefront_child_enqueue_styles() {
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('storefront-child-style', get_stylesheet_directory_uri() . '/style.css', ['storefront-style']);
}
add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');


require get_stylesheet_directory() . '/includes/post-types/cities.php';
require get_stylesheet_directory() . '/includes/meta-boxes/cities-meta-boxes.php';
require get_stylesheet_directory() . '/includes/taxonomies/countries-taxonomy.php';
require get_stylesheet_directory() . '/includes/widgets/city-temperature-widget.php';

function enqueue_cities_search_script() {
    wp_enqueue_script('ajax-search', get_stylesheet_directory_uri() . '/js/ajax-search.js', ['jquery'], null, true);
    wp_localize_script('ajax-search', 'ajaxurl', admin_url('admin-ajax.php')); // Pass Ajax URL
}
add_action('wp_enqueue_scripts', 'enqueue_cities_search_script');


function cities_search_ajax_handler() {
    global $wpdb;

    // Get the search term
    $term = sanitize_text_field($_POST['term']);

    // Query the database for cities
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT p.ID, p.post_title, pm1.meta_value AS latitude, pm2.meta_value AS longitude
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'latitude'
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'longitude'
        WHERE p.post_type = 'cities' AND p.post_status = 'publish' AND p.post_title LIKE %s
    ", '%' . $wpdb->esc_like($term) . '%'));

    // Generate table rows
    foreach ($results as $row) {
        $country = get_the_terms($row->ID, 'countries')[0]->name ?? 'N/A';
        $latitude = $row->latitude;
        $longitude = $row->longitude;

        // Fetch temperature using OpenWeatherMap API
        $api_key = 'd3ae48488e0bdd02d8d443f9f4a91643'; // Replace with OpenWeatherMap API key
        $api_url = "http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";
        $response = wp_remote_get($api_url);
        $weather_data = wp_remote_retrieve_body($response);
        $weather = json_decode($weather_data, true);
        $temperature = $weather['main']['temp'] ?? 'N/A';

        echo '<tr>';
        echo '<td>' . esc_html($country) . '</td>';
        echo '<td>' . esc_html($row->post_title) . '</td>';
        echo '<td>' . esc_html($temperature) . '°C</td>';
        echo '</tr>';
    }

    wp_die(); // Terminate Ajax response
}

add_action('wp_ajax_cities_search', 'cities_search_ajax_handler');
add_action('wp_ajax_nopriv_cities_search', 'cities_search_ajax_handler');

add_action('before_cities_table', function () {
    echo '<p>This content is displayed before the Cities table.</p>';
});

add_action('after_cities_table', function () {
    echo '<p>This content is displayed after the Cities table.</p>';
});


