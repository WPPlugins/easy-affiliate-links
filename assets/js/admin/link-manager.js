jQuery(document).ready(function($) {
    jQuery('.eafl-select').select2_eafl();

    // Select Link Dropdown
    jQuery('.eafl-link-select').select2_eafl({
        width: '250px',
        ajax: {
            type: 'POST',
            url: eafl_link_manager.ajax_url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    action: 'eafl_search_links',
                    security: eafl_link_manager.nonce,
                    search: params.term
                };
            },
            processResults: function (out, params) {
                return {
                    results: out.data.links_with_id,
                };
            },
            cache: true
        },
        minimumInputLength: 1,
    });
});