<?php
class City_Temperature_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'city_temperature_widget', // Base ID
            'City Temperature Widget', // Name
            [
                'description' => 'Displays a city name and its current temperature.',
            ]
        );
    }

    // The output of the widget on the front end
    public function widget($args, $instance) {
        // Get widget settings
        $city_id = $instance['city_id'] ?? '';

        if (!$city_id) {
            echo 'City not selected.';
            return;
        }

        // Fetch city details
        $city_name = get_the_title($city_id);
        $latitude = get_post_meta($city_id, 'latitude', true);
        $longitude = get_post_meta($city_id, 'longitude', true);

        if (!$latitude || !$longitude) {
            echo 'City coordinates are missing.';
            return;
        }

        // Fetch temperature using OpenWeatherMap API
        $api_key = 'd3ae48488e0bdd02d8d443f9f4a91643'; // Replace with OpenWeatherMap API key
        $api_url = "http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            echo 'Unable to fetch temperature.';
            return;
        }

        $data = wp_remote_retrieve_body($response);
        $weather = json_decode($data, true);

        // Check if the API returned a valid temperature
        $temperature = $weather['main']['temp'] ?? 'N/A';

        // Display the widget content
        echo $args['before_widget'];
        if (!empty($args['before_title']) && !empty($city_name)) {
            echo $args['before_title'] . $city_name . $args['after_title'];
        }
        echo '<p>Temperature: ' . esc_html($temperature) . '°C</p>';
        echo $args['after_widget'];
    }

    // The settings form for the widget in the admin area
    public function form($instance) {
        $city_id = $instance['city_id'] ?? '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>">City:</label>
            <select id="<?php echo $this->get_field_id('city_id'); ?>" name="<?php echo $this->get_field_name('city_id'); ?>" style="width: 100%;">
                <option value="">Select a City</option>
                <?php
                // Query all cities
                $cities = get_posts([
                    'post_type' => 'cities',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                ]);

                foreach ($cities as $city) {
                    $selected = ($city_id == $city->ID) ? 'selected' : '';
                    echo '<option value="' . esc_attr($city->ID) . '" ' . $selected . '>' . esc_html($city->post_title) . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    // Save widget settings
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['city_id'] = sanitize_text_field($new_instance['city_id']);
        return $instance;
    }
}

// Register the widget
add_action('widgets_init', function () {
    register_widget('City_Temperature_Widget');
});
