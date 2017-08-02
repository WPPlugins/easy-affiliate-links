var eafl_admin = eafl_admin || {};

eafl_admin.reset_clicks = function(link_id) {
	var data = {
			action: 'eafl_reset_clicks',
			security: eafl_manage.nonce,
			link_id: link_id,
	};

	jQuery.post(eafl_manage.ajax_url, data, function() {
		eafl_admin.reload_datatable();
	});
};

eafl_admin.delete_link = function(link_id) {
	var data = {
			action: 'eafl_delete_link',
			security: eafl_manage.nonce,
			link_id: link_id,
	};

	jQuery.post(eafl_manage.ajax_url, data, function() {
		eafl_admin.reload_datatable();
	});
};

eafl_admin.create_term = function(name) {
	var data = {
			action: 'eafl_create_term',
			security: eafl_manage.nonce,
			name: name
	};

	jQuery.post(eafl_manage.ajax_url, data, function() {
		eafl_admin.reload_datatable();
	});
};

eafl_admin.delete_or_merge_term = function(term_id, new_term_id) {
	var data = {
			action: 'eafl_delete_or_merge_term',
			security: eafl_manage.nonce,
			term_id: term_id,
			new_term_id: new_term_id
	};

	jQuery.post(eafl_manage.ajax_url, data, function() {
		eafl_admin.reload_datatable();
	});
};

eafl_admin.reload_datatable = function() {
	var instances = jQuery.tooltipster.instances();
	jQuery.each(instances, function(i, instance){
		instance.close();
	});
	jQuery('.eafl-manage-datatable').DataTable().ajax.reload(null, false);
};

jQuery(document).ready(function($) {
	new Clipboard('.eafl-copy-link');

	// Get ID of datatable that's active on this page
	var eafl_active_datatable = '';
	jQuery('.eafl-manage-datatable').each(function() {
		eafl_active_datatable = jQuery(this).attr('id');
	});

	if(eafl_active_datatable == 'eafl-manage-links') {
		var default_order = [ 1, 'desc' ];
	} else {
		var default_order = [ 0, 'desc' ];
	}

	// Init datatable
	$.fn.dataTable.ext.errMode = 'throw';
	
	var eafl_datatable = jQuery('.eafl-manage-datatable').DataTable( {
		pageLength: 25,
		order: default_order,
		scrollX: true,
		serverSide: true,
		ajax: {
			url: eafl_manage.ajax_url,
			type: 'POST',
			data: function ( d ) {
				d.selection = eafl_admin.active_args != undefined && eafl_admin.active_args.selection ? eafl_admin.active_args.selection : '';
				d.action = 'eafl_manage_datatable';
				d.security = eafl_manage.nonce;
				d.table = eafl_active_datatable;
			}
		},
		drawCallback: function() {
			var instances = jQuery.tooltipster.instances();
			jQuery.each(instances, function(i, instance){
				instance.close();
			});
			
			// Add tooltips
			jQuery('.eafl-manage-links-actions').tooltipster({
				content: '<div class="eafl-manage-links-actions-tooltip"><div class="tooltip-header">&nbsp;</div><a href="#" class="eafl-manage-links-actions-edit">Edit link</a><a href="#" class="eafl-manage-links-actions-reset-clicks">Reset Clicks</a><a href="#" class="eafl-manage-links-actions-delete">Delete link</a></div>',
				contentAsHTML: true,
				functionBefore: function() {
					var instances = jQuery.tooltipster.instances();
					jQuery.each(instances, function(i, instance){
						instance.close();
					});
				},
				functionReady: function(instance, helper) {
					var id = parseInt(jQuery(helper.origin).data('id')),
						name = jQuery('#eafl-manage-links-name-' + id).text();

					jQuery(helper.tooltip).find('a').data('id', id);
					jQuery(helper.tooltip).find('.tooltip-header').text('#' + id + ' - ' + name);
				},
				interactive: true,
				delay: 0,
				side: 'right',
				trigger: 'custom',
				triggerOpen: {
					mouseenter: true,
					touchstart: true
				},
				triggerClose: {
					click: true,
					tap: true
				},
			});

			jQuery('.eafl-manage-categories-actions').tooltipster({
				content: '<div class="eafl-manage-categories-actions-tooltip"><div class="tooltip-header">&nbsp;</div><a href="#" class="eafl-manage-categories-actions-merge">Merge into another category</a><a href="#" class="eafl-manage-categories-actions-delete">Delete category</a></div>',
				contentAsHTML: true,
				functionBefore: function() {
					var instances = jQuery.tooltipster.instances();
					jQuery.each(instances, function(i, instance){
						instance.close();
					});
				},
				functionReady: function(instance, helper) {
					var id = parseInt(jQuery(helper.origin).data('id')),
						name = jQuery('#eafl-manage-categories-name-' + id).text();

					jQuery(helper.tooltip).find('a').data('id', id);
					jQuery(helper.tooltip).find('.tooltip-header').text('#' + id + ' - ' + name);
				},
				interactive: true,
				delay: 0,
				side: 'right',
				trigger: 'custom',
				triggerOpen: {
					mouseenter: true,
					touchstart: true
				},
				triggerClose: {
					click: true,
					tap: true
				},
			});
		}
	} );

	jQuery(document).on('click', '.eafl-button-add-link', function() {
		eafl_admin.open_modal(false, {
			link_id: 0
		});
	});

	jQuery(document).on('click', '.eafl-button-add-category', function() {
		var name = prompt('How do you want to name this new category?', '');

		if(name) {
			eafl_admin.create_term(name);
		}
	});

	jQuery(document).on('click', '.eafl-manage-links-actions-reset-clicks', function() {
		var id = jQuery(this).data('id'),
			name = jQuery('#eafl-manage-links-name-' + id).text();

		if(confirm('Are you sure you want to reset the click counter for "' + name + '"?')) {
			eafl_admin.reset_clicks(id);
		}
	});

	jQuery(document).on('click', '.eafl-manage-links-actions-edit', function() {
		var id = jQuery(this).data('id');

		eafl_admin.open_modal(false, {
			link_id: id
		});
	});

	jQuery(document).on('click', '.eafl-manage-links-actions-delete', function() {
		var id = jQuery(this).data('id'),
			name = jQuery('#eafl-manage-links-name-' + id).text();

		if(confirm('Are you sure you want to delete "' + name + '"? All shortcodes and links to this shortlink will be broken.')) {
			eafl_admin.delete_link(id);
		}
	});

	jQuery(document).on('click', '.eafl-manage-categories-actions-merge', function() {
		var id = jQuery(this).data('id'),
			name = jQuery('#eafl-manage-categories-name-' + id).text();
		
		var new_term = parseInt(prompt('What is the ID of the category that you want to merge "' + name + '" into?', ''));
		if(new_term) {
			eafl_admin.delete_or_merge_term(id, new_term);
		}
	});

	jQuery(document).on('click', '.eafl-manage-categories-actions-delete', function() {
		var id = jQuery(this).data('id'),
			name = jQuery('#eafl-manage-categories-name-' + id).text();
		
		if(confirm('Are you sure you want to delete "' + name + '"?')) {
			eafl_admin.delete_or_merge_term(id, 0);
		}
	});
});
