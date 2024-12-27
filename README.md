# Cities and Temperatures - Custom WordPress Implementation

This project implements a custom WordPress feature for displaying a list of cities, their associated countries, and the current temperature fetched from the OpenWeatherMap API. The implementation includes custom post types, taxonomies, meta fields, Ajax search functionality, and hooks for extensibility.

---

## Features

1. **Custom Post Type: Cities**
   - A custom post type `cities` for storing city information.
   - Supports the addition of latitude and longitude through custom meta fields.

2. **Custom Taxonomy: Countries**
   - A hierarchical taxonomy `countries` associated with the `cities` post type for categorizing cities by their countries.

3. **Dynamic Temperature Fetching**
   - Integrates with the OpenWeatherMap API to display the current temperature for each city based on its latitude and longitude.

4. **Ajax-Powered Search**
   - A real-time search field for filtering cities based on their names.

5. **Custom Hooks**
   - Extensible hooks `before_cities_table` and `after_cities_table` for injecting custom content before and after the cities table.

---

## File Structure


storefront-child/
│
├── includes/
│   ├── post-types/
│   │   └── cities.php             # Registers the Cities custom post type
│   ├── taxonomies/
│   │   └── countries-taxonomy.php # Registers the Countries taxonomy
│   ├── meta-boxes/
│   │   └── cities-meta-boxes.php  # Adds meta boxes for latitude and longitude
│   ├── widgets/
│   │   └── city-temperature-widget.php  # Widget for displaying city and temperature
│   └── js/
│       └── ajax-search.js         # JavaScript for Ajax-powered city search
│
├── template-cities-list.php       # Custom template for listing cities, countries, and temperatures
├── front-page.php                 # Displays table headers for cities
├── functions.php                  # Includes all custom functionality
├── style.css                      # Styles for the cities table and search form



---

## Setup Instructions

1. **Install the Storefront Child Theme**
   - Download or clone the child theme into `wp-content/themes/` directory.
   - Activate the child theme in the WordPress admin panel.

2. **Configure the OpenWeatherMap API**
   - Obtain an API key from [OpenWeatherMap](https://openweathermap.org/).

3. **Ensure Data Exists**
   - Run the `add_sample_cities_data` function (located in `functions.php`) to add sample data for cities and countries.

4. **Assign the Custom Template**
   - Create a new page in WordPress and assign the "Cities List" template to it.

5. **Add the Widget**
   - Go to **Appearance > Widgets** and add the "City Temperature Widget" to a sidebar or widget area.

---

## Customization

1. **Add Content Before/After Cities Table**
   - Use the hooks `before_cities_table` and `after_cities_table` in `functions.php`:
     ```php
     add_action('before_cities_table', function () {
         echo '<p>This content appears before the table.</p>';
     });

     add_action('after_cities_table', function () {
         echo '<p>This content appears after the table.</p>';
     });
     ```

2. **Customize Styles**
   - Edit the `style.css` file to modify the appearance of the cities table and search form.

3. **Modify Temperature Caching**
   - Temperature data is cached for 1 hour using WordPress transients. Adjusted the duration in `functions.php`:
     ```php
     set_transient($cache_key, $temperature, HOUR_IN_SECONDS);
     ```

---

## Technical Details

### Custom Post Type
- Registered using `register_post_type` in `includes/post-types/cities.php`.

### Custom Taxonomy
- Registered using `register_taxonomy` in `includes/taxonomies/countries-taxonomy.php`.

### Meta Boxes
- Latitude and longitude are stored as custom fields via meta boxes in `includes/meta-boxes/cities-meta-boxes.php`.

### Widget
- The widget fetches city information and displays the current temperature using the OpenWeatherMap API.

### Ajax Search
- Searches cities dynamically using `admin-ajax.php`.
- Implements the `cities_search_ajax_handler` function in `functions.php`.

### API Integration
- Fetches temperature data from OpenWeatherMap using latitude and longitude.
- Handles API errors, invalid responses, and rate limits gracefully.

---

## Example Output

### Front Page (Headers Only)

Country	City	Temperature


### Cities List Page
| Country       | City       | Temperature |
|---------------|------------|-------------|
| United States | New York   | 15°C        |
| Canada        | Toronto    | 12°C        |
| France        | Paris      | 20°C        |
| Japan         | Tokyo      | 18°C        |
| Australia     | Sydney     | 25°C        |

---

## Known Issues

1. **API Rate Limits**: Free OpenWeatherMap API keys have limited requests per minute.
   - Use caching to minimize API calls.
   - Consider upgrading to a paid API plan for higher limits.

2. **Empty Data**: Ensure that all cities have valid latitude and longitude values.

3. **N/A Temperature**: Appears if:
   - API fails to fetch data.
   - Latitude/Longitude is invalid.
   - The API key is incorrect or expired.

---

## Support

For other concern, feel free to contact the me with my email deralddlared@gmail.com.
