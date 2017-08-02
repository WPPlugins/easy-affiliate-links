var eafl_admin = eafl_admin || {};

eafl_admin.active_editor_id = false;
eafl_admin.active_args = {};

eafl_admin.disable_menu = function() {
		jQuery('.eafl-frame-menu').find('.eafl-menu-item').hide();
		jQuery('.eafl-menu-hidden').show();
};

eafl_admin.open_modal = function(editor_id, args) {
		args = args === undefined ? {} : args;
		eafl_admin.active_args = args;

		// Enable menu items
		jQuery('.eafl-menu-item').show();
		jQuery('.eafl-menu-hidden').hide();

		eafl_admin.active_editor_id = editor_id;
		jQuery('.eafl-modal-container').show();

		// Default to first menu item
		jQuery('.eafl-menu').find('.eafl-menu-item').first().click();

		// Init tabs
		var tabs = jQuery('.eafl-router').find('.eafl-menu-item');
		jQuery(tabs).each(function() {
			init_callback = jQuery(this).data('init');

			if (init_callback && typeof eafl_admin[init_callback] == 'function') {
					eafl_admin[init_callback](args);
			}
		});
};

eafl_admin.close_modal = function() {
		eafl_admin.active_editor_id = false;
		jQuery('.eafl-menu').removeClass('visible');
		jQuery('.eafl-modal-container').hide();
};

eafl_admin.start_loader = function(button) {
	button
		.prop('disabled', true)
		.css('width', button.outerWidth())
		.data('text', button.html())
		.html('...');
};

eafl_admin.stop_loader = function(button) {
	button
		.prop('disabled', false)
		.css('width', '')
		.html(button.data('text'));
};

eafl_admin.shortcode_escape_map = {
		'"': "'"
};

eafl_admin.shortcode_escape = function(text) {
		return String(text).replace(/["]/g, function(s) {
				return eafl_admin.shortcode_escape_map[s];
		});
};

eafl_admin.replace_shortcode_in_editor = function(replace_shortcode_id, id, name, text) {
	name = eafl_admin.shortcode_escape(name);
	text = eafl_admin.shortcode_escape(text);

	var shortcode = '[eafl id="' + id + '" name="' + name + '" text="' + text + '"]';

	if (eafl_admin.active_editor_id) {
		if (typeof tinyMCE !== 'undefined' && tinyMCE.get(eafl_admin.active_editor_id) && !tinyMCE.get(eafl_admin.active_editor_id).isHidden()) {
			tinyMCE.get(eafl_admin.active_editor_id).focus(true);

			var content = tinyMCE.activeEditor.getContent();

			// Make sure we replace the shortcode we were editing
			var shortcode_id = 0;
			content = content.replace( /\[eafl([^\]]*)\]/g, function( match ) {
				shortcode_id++;
				if(shortcode_id != replace_shortcode_id) {
					return match;
				} else {
					return shortcode;
				}
			});

			tinyMCE.activeEditor.setContent(content);
			tinyMCE.activeEditor.execCommand('mceRepaint');
		}
	}
};

eafl_admin.add_shortcode_to_editor = function(id, name, text) {
	name = eafl_admin.shortcode_escape(name);
	text = eafl_admin.shortcode_escape(text);

	var shortcode = '[eafl id="' + id + '" name="' + name + '" text="' + text + '"]';

	eafl_admin.add_text_to_editor(shortcode);
};

eafl_admin.add_text_to_editor = function(text) {
	if (eafl_admin.active_editor_id) {
		if (typeof tinyMCE == 'undefined' || !tinyMCE.get(eafl_admin.active_editor_id) || tinyMCE.get(eafl_admin.active_editor_id).isHidden()) {
			var current = jQuery('textarea#' + eafl_admin.active_editor_id).val();
			jQuery('textarea#' + eafl_admin.active_editor_id).val(current + text);
		} else {
			tinyMCE.get(eafl_admin.active_editor_id).focus(true);
			tinyMCE.activeEditor.execCommand('mceInsertContent', false, text);
		}
	}
};

jQuery(document).ready(function($) {
		// Opening Modal
		jQuery(document).on('click', '.eafl-modal-button', function() {
				var editor_id = jQuery(this).data('editor');
				eafl_admin.open_modal(editor_id);
		});

		// Closing Modal
		jQuery('.eafl-modal-container').on('click', '.eafl-modal-close, .eafl-modal-backdrop', function() {
				eafl_admin.close_modal();
		});

		// Modal Menu
		jQuery('.eafl-menu').on('click', '.eafl-menu-item', function() {
				var menu_item = jQuery(this),
					menu_target = menu_item.data('menu'),
					menu_tab = menu_item.data('tab');

				// Hide Menu if on Mobile
				jQuery('.eafl-menu').removeClass('visible');

				// Set clicked on tab as the active one
				jQuery('.eafl-menu').find('.eafl-menu-item').removeClass('active');
				menu_item.addClass('active');

				// Show correct menu
				jQuery('.eafl-frame-router').find('.eafl-router').removeClass('active');
				jQuery('.eafl-frame-router').find('#eafl-menu-' + menu_target).addClass('active');

				// Show the first tab as active or whichever tab was passed along
				var active_tab = false;
				jQuery('.eafl-router').find('.eafl-menu-item').removeClass('active');
				jQuery('.eafl-frame-router').find('#eafl-menu-' + menu_target).find('.eafl-menu-item').each(function(index) {
						if (index === 0 || jQuery(this).data('tab') == menu_tab) {
								active_tab = jQuery(this);
						}
				});

				if (active_tab) {
					active_tab.click();
				}

				// Change main title
				jQuery('.eafl-frame-title').find('h1').text(menu_item.text());
		});

		// Modal Menu on Mobile
		jQuery('.eafl-modal-container').on('click', '.eafl-frame-title', function() {
				jQuery('.eafl-menu').toggleClass('visible');
		});

		// Modal Tabs
		jQuery('.eafl-router').on('click', '.eafl-menu-item', function() {
			var menu_item = jQuery(this),
				tab_target = menu_item.data('tab'),
				tab_button = menu_item.data('button');

			// Set clicked on tab as the active one
			jQuery('.eafl-router').find('.eafl-menu-item').removeClass('active');
			menu_item.addClass('active');

			// Hide action button if no callback is set
			if (menu_item.data('callback')) {
					jQuery('.eafl-button-action').text(tab_button).show();
			} else {
					jQuery('.eafl-button-action').hide();
			}

			// Show correct tab
			jQuery('.eafl-frame-content').find('.eafl-frame-content-tab').removeClass('active');
			jQuery('.eafl-frame-content').find('#eafl-tab-' + tab_target).addClass('active');

			// Focus on first input
			setTimeout(function() {
				jQuery('.eafl-frame-content-tab.active').find('input').first().focus();
			}, 200);
		});

		// Insert or Update Button
		jQuery('.eafl-button-action').on('click', function() {
			var active_tab = jQuery('.eafl-router.active').find('.eafl-menu-item.active'),
				callback = active_tab.data('callback');

			if (typeof eafl_admin[callback] == 'function') {
				eafl_admin[callback](jQuery(this));
			}
		});

		// Prevent Divi Builder bug.
		jQuery('.eafl-modal-container').keydown( function(e) {
			e.stopPropagation();
		});
});
