var eafl_admin = eafl_admin || {};

eafl_admin.editing_link = 0;
eafl_admin.set_link = function(args) {
	var link_id = args.link_id ? args.link_id : 0;

	eafl_admin.editing_link = link_id;
	eafl_admin.clear_link_fields();
	if(typeof eaflp_admin !== 'undefined') {
		eaflp_admin.clear_link_fields();
	}

	if (link_id == 0) {
        // Make sure to use correct button text.
		var button = jQuery('.eafl-button-action');

		jQuery('.eafl-router.active').find('.eafl-menu-item').each(function() {
			jQuery(this).data('button', eafl_modal.text.action_button_insert);
		});
		button.text(eafl_modal.text.action_button_insert);
        
        // Set correct tab title.
        jQuery('.eafl-router.active').find('.eafl-menu-item[data-tab="easy-affiliate-links-create-edit-link"]').text(eafl_modal.text.create_link);

        // Make sure form is visible.
        jQuery('.eafl-link-details-form').removeClass('eafl-loading');
	} else {
        // Change button text.
        var button = jQuery('.eafl-button-action');

		jQuery('.eafl-router.active').find('.eafl-menu-item').each(function() {
			jQuery(this).data('button', eafl_modal.text.action_button_update);
		});
		button.text(eafl_modal.text.action_button_update);

        // Set correct tab title.
        jQuery('.eafl-router.active').find('.eafl-menu-item[data-tab="easy-affiliate-links-create-edit-link"]').text(eafl_modal.text.edit_link);

        // Hide Select Link tab.
        jQuery('.eafl-router.active').find('.eafl-menu-item[data-tab="easy-affiliate-links-select-link"]').hide();
        jQuery('.eafl-router.active').find('.eafl-menu-item[data-tab="easy-affiliate-links-edit-text"]').click();

        // Load link data.
        var data = {
			action: 'eafl_get_link',
			security: eafl_modal.nonce,
			link_id: link_id
		};

		jQuery('.eafl-link-details-form').addClass('eafl-loading');

		jQuery.post(eafl_modal.ajax_url, data, function(out) {
			jQuery('.eafl-link-details-form').removeClass('eafl-loading');

			if (out.success) {
				eafl_admin.set_link_fields(out.data.link);
				if(typeof eaflp_admin !== 'undefined') {
					eaflp_admin.set_link_fields(out.data.link);
				}
			}
		}, 'json');
    }
};

eafl_admin.set_link_text = function(args) {
	var link_id = args.link_id ? args.link_id : 0;

	if (link_id == 0) {
        // Only show tab when editing a link.
		jQuery('.eafl-router.active').find('.eafl-menu-item[data-tab="easy-affiliate-links-edit-text"]').hide();
	} else {
        jQuery('#eafl-link-actual-text').val(args.selection);
    }
};

eafl_admin.update_link_text = function(button) {
    var text = jQuery('#eafl-link-actual-text').val().trim();
    text = text == '' ? eafl_admin.active_args.selection : text;

    eafl_admin.replace_shortcode_in_editor(eafl_admin.active_args.shortcode_id, eafl_admin.active_args.link_id, eafl_admin.active_args.name, text);
    eafl_admin.close_modal();
};

eafl_admin.clear_link_fields = function() {
    jQuery('#eafl-link-name').val('');
    jQuery('#eafl-link-description').val('');
    jQuery('#eafl-link-categories').val(null).trigger('change');
    jQuery('#eafl-link-text').val('');
    jQuery('.eafl-link-text').not('#eafl-link-text').remove();

    jQuery('#eafl-link-url').val('');
    jQuery('#eafl-link-slug').val('').trigger('change');
    jQuery('#eafl-link-target-default').prop('checked', true);
    jQuery('#eafl-link-redirect-type-default').prop('checked', true);
    jQuery('#eafl-link-nofollow-default').prop('checked', true);
    
};

eafl_admin.set_link_fields = function(link) {
    jQuery('#eafl-link-name').val(link.name);
    jQuery('#eafl-link-description').val(link.description);

    // Link categories
    var term_ids = [],
        select = jQuery('#eafl-link-categories');

    for (var i = 0, l = link.categories.length; i < l; i++) {
        var term = link.categories[i];
        term_ids.push(term.term_id);

        // Add term to options if not in there.
        if (select.find('option[value=' + term.term_id + ']').length === 0) {
            select.append('<option value="' + term.term_id + '">' + term.name + '</option>');
        }
    }
    select.val(term_ids).trigger('change');

    // Link Text
    for (var i = 0, l = link.text.length; i < l; i++) {
        if(i>0) {
            jQuery('#eafl-link-text-add-variant').click();
        }
        jQuery('.eafl-link-text').last().val(link.text[i]);
    }

    jQuery('#eafl-link-url').val(link.url);
    jQuery('#eafl-link-slug').val(link.slug).trigger('change');
    jQuery('#eafl-link-target-' + link.target ).prop('checked', true);
    jQuery('#eafl-link-redirect-type-' + link.redirect_type).prop('checked', true);
    jQuery('#eafl-link-nofollow-' + link.nofollow).prop('checked', true);
};

eafl_admin.insert_update_link = function(button) {
	// Link Details
    var link = {
        name: jQuery('#eafl-link-name').val(),
        description: jQuery('#eafl-link-description').val(),
        categories: jQuery('#eafl-link-categories').val(),
        url: jQuery('#eafl-link-url').val(),
        slug: jQuery('#eafl-link-slug').val(),
        target: jQuery('input[name="eafl-link-target"]:checked').val(),
        redirect_type: jQuery('input[name="eafl-link-redirect-type"]:checked').val(),
        nofollow: jQuery('input[name="eafl-link-nofollow"]:checked').val(),
	};

    // Link Text
    var link_text = [];
    jQuery('.eafl-link-text').each(function() {
        var text = jQuery(this).val();
        if(text) {
            link_text.push(text);
        }
    });
    link.text = link_text;

	// Add any Premium fields
	if(typeof eaflp_admin !== 'undefined') {
		link = eaflp_admin.insert_update_link(link);
	}
 
	// Ajax call to link saver
	var data = {
		action: 'eafl_save_link',
		security: eafl_modal.nonce,
		link_id: eafl_admin.editing_link,
		link: link
	};

	eafl_admin.start_loader(button);

	jQuery.post(eafl_modal.ajax_url, data, function(out) {
			eafl_admin.stop_loader(button);

			if (out.success) {
					if (eafl_admin.editing_link === 0) {
                        var text = eafl_admin.active_args.selection == '' ? out.data.text : eafl_admin.active_args.selection;
						eafl_admin.add_shortcode_to_editor(out.data.id, out.data.name, text);
					} else if(eafl_admin.active_editor_id) {
						eafl_admin.replace_shortcode_in_editor(eafl_admin.active_args.shortcode_id, out.data.id, out.data.name, out.data.text);
					}

					if(jQuery('.eafl-manage-datatable').length > 0) {
						jQuery('.eafl-manage-datatable').DataTable().ajax.reload(null, false);
					}

					eafl_admin.close_modal();
			}
	}, 'json');
};

eafl_admin.update_link_preview = function() {
    var slug_input = jQuery('#eafl-link-slug'),
        slug_val = slug_input.val(),
        slug = eafl_admin.convert_to_slug(slug_val);

    if(slug != slug_val) {
        slug_input.val(slug);
    }

    if(slug) {
        slug += '/';
    }

    jQuery('#eafl-link-shortlink-preview').text(slug);
};

eafl_admin.convert_to_slug = function(slug) {
    return slug.toLowerCase()
        .replace(/ /g,'-')
        .replace(/[-]+/g, '-')
        .replace(/[^\w-]+/g,'');
};

jQuery(document).ready(function($) {
    jQuery('.eafl-modal').on('keydown', 'input', function(e) {
        var keycode = event.KeyCode ? event.KeyCode : event.which;

        if(keycode == '13') {
            jQuery('.eafl-button-action').click();
        }
    });

    jQuery('#eafl-link-categories').select2_eafl({
	    width: '100%',
		tags: true
	});

    jQuery('#eafl-link-slug').on('keyup change', function() {
        eafl_admin.update_link_preview();
    });

    jQuery('#eafl-link-text-add-variant').on('click', function(e) {
        e.preventDefault();

        var input = jQuery('#eafl-link-text').clone();
        input.prop('id', '')
            .val('');

        jQuery(this).before(input);
    });
});