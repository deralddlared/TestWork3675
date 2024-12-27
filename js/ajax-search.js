jQuery(document).ready(function ($) {
    $('#city-search-button').on('click', function () {
        var searchTerm = $('#city-search-input').val();

        // Send Ajax request
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cities_search',
                term: searchTerm
            },
            success: function (response) {
                $('#cities-table tbody').html(response);
            },
            error: function () {
                alert('Error loading data.');
            }
        });
    });
});
