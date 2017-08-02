var eafl_admin = eafl_admin || {};

jQuery(document).ready(function($) {
	// Get ID of datatable that's active on this page
	var eafl_statistics_datatable = '';
	jQuery('.eafl-statistics-datatable').each(function() {
		eafl_statistics_datatable = jQuery(this).attr('id');
	});

	// Init datatable
	$.fn.dataTable.ext.errMode = 'throw';
	
	var eafl_datatable = jQuery('.eafl-statistics-datatable').DataTable( {
		pageLength: 25,
		order: [ 0, 'desc' ],
		search: false,
		scrollX: true,
		serverSide: true,
		ajax: {
			url: eafl_statistics.ajax_url,
			type: 'POST',
			data: function ( d ) {
				d.selection = eafl_admin.active_args != undefined && eafl_admin.active_args.selection ? eafl_admin.active_args.selection : '';
				d.action = 'eafl_statistics_datatable';
				d.security = eafl_statistics.nonce;
				d.table = eafl_statistics_datatable;
			}
		}
	} );
});
