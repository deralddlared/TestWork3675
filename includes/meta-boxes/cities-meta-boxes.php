<?php
function add_cities_meta_boxes() {
    add_meta_box(
        'cities_coordinates',
        'City Coordinates',
        'render_cities_meta_box',
        'cities',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_cities_meta_boxes');

function render_cities_meta_box($post) {
    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);
    ?>
    <label for="latitude">Latitude:</label>
    <input type="text" id="latitude" name="latitude" value="<?php echo esc_attr($latitude); ?>">
    <br>
    <label for="longitude">Longitude:</label>
    <input type="text" id="longitude" name="longitude" value="<?php echo esc_attr($longitude); ?>">
    <?php
}

function save_cities_meta_boxes($post_id) {
    if (array_key_exists('latitude', $_POST)) {
        update_post_meta($post_id, 'latitude', sanitize_text_field($_POST['latitude']));
    }
    if (array_key_exists('longitude', $_POST)) {
        update_post_meta($post_id, 'longitude', sanitize_text_field($_POST['longitude']));
    }
}
add_action('save_post', 'save_cities_meta_boxes');
