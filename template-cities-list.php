<?php
/**
 * Template Name: Cities List
 * Description: Displays a table of countries, cities, and temperatures with a search field.
 */

get_header(); // Load the header template

do_action('before_cities_table'); // Custom hook before the table

?>

<div class="cities-list-container">
    <h1>Cities List</h1>
    
    <!-- Search Form -->
    <form id="city-search-form">
        <input type="text" id="city-search-input" placeholder="Search for a city">
        <button type="button" id="city-search-button">Search</button>
    </form>

    <!-- Cities Table -->
    <table id="cities-table">
        <thead>
            <tr>
                <th>Country</th>
                <th>City</th>
                <th>Temperature</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here via WP Ajax -->
        </tbody>
    </table>
</div>

<?php

do_action('after_cities_table'); // Custom hook after the table

get_footer(); // Load the footer template
