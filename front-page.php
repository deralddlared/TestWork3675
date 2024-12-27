<?php
get_header();

global $wpdb;
$results = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm1.meta_value AS latitude, pm2.meta_value AS longitude
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'latitude'
    INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'longitude'
    WHERE p.post_type = 'cities' AND p.post_status = 'publish'
");

echo '<h1>Cities</h1>';

// Action hook before the table
do_action('before_cities_table');

echo '<table>';
echo '<tr><th>Country</th><th>City</th><th>Temperature</th></tr>';
foreach ($results as $row) {
    $country = get_the_terms($row->ID, 'countries')[0]->name ?? '';
    $latitude = $row->latitude;
    $longitude = $row->longitude;

    $api_key = 'd3ae48488e0bdd02d8d443f9f4a91643'; // Replace with OpenWeatherMap API key
    $url = "http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";
    $response = wp_remote_get($url);
    $data = wp_remote_retrieve_body($response);
    $weather = json_decode($data);
    $temperature = $weather->main->temp ?? 'N/A';

    echo "<tr><td>{$country}</td><td>{$row->post_title}</td><td>{$temperature}°C</td></tr>";
}
echo '</table>';

// Action hook after the table
do_action('after_cities_table');

get_footer();
