var eafl_admin = eafl_admin || {};

eafl_admin.link_overview = function() {
    jQuery('.eafl-manage-datatable').DataTable().ajax.reload(null, false);
};

eafl_admin.select_link = function(row) {
    var id = row.find('.eafl-manage-links-id').text().substring(1),
        name = row.find('.eafl-manage-links-name').text(),
        text = row.find('.eafl-select-text').val();

    if(text == 'eafl_custom_link_text') {
        text = prompt(eafl_modal.text.custom_link_text + ': ' + name);
    }

    if(text) {
        eafl_admin.add_shortcode_to_editor(id, name, text);
        eafl_admin.close_modal();
    } else {
        row.find('.eafl-select-text').find('option:eq(0)').prop('selected', true);
    }
};

jQuery(document).ready(function($) {
    jQuery('.eafl-manage-datatable').on('change', '.eafl-select-text', function() {
        eafl_admin.select_link(jQuery(this).parents('tr'));
    });

    jQuery('.eafl-manage-datatable').on('click', '.eafl-add-link', function() {
        eafl_admin.select_link(jQuery(this).parents('tr'));
    });
});