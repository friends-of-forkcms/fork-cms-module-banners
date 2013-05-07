/**
 * Interaction for the banners module
 *
 * @author	Lowie Benoot <lowiebenoot@netlash.com>
 */
jsBackend.banners =
{
	// init, something like a constructor
	init: function()
	{
		// init
		jsBackend.banners.controls.init();
		jsBackend.banners.settings.init();

		// do meta
		if($('#title').length > 0) $('#title').doMeta();
	}
}

jsBackend.banners.controls =
{
	// init, something like a constructor
	init: function()
	{
		// change the tracker url
		$('span #generatedUrl').html(encodeURIComponent($('#url').val()));

		// change tracker url on keyup
		$('#url').keyup(function()
		{
			$('span #generatedUrl').html(encodeURIComponent($('#url').val()));
		});

		if(jsBackend.current.action == 'add_group')
		{
			// initial hide/show from rows
			jsBackend.banners.controls.filterBySize();

			// input select on change function
			$('#size').on('change', function()
			{
				// filter the datagrid by the selected size
				jsBackend.banners.controls.filterBySize();

				// uncheck the checked checkboxes
				jsBackend.banners.controls.uncheckChecked();
			});
		}

		if($('#showPermanently').length > 0)
		{
			// initial enable/disable
			jsBackend.banners.controls.enableOrDisableDates();

			// input select on change function
			$('#showPermanently').on('change', function()
			{
				// filter the datagrid by the selected size
				jsBackend.banners.controls.enableOrDisableDates();
			});
		}
	},

	// filter the datagrid by the selected size
	enableOrDisableDates: function()
	{
		if($('#showPermanently').is(':checked')) $('#startDate, #endDate, #startTime, #endTime').prop('disabled', true).addClass('disabled');
		else $('#startDate, #endDate, #startTime, #endTime').prop('disabled', false).removeClass('disabled');
	},

	// filter the datagrid by the selected size
	filterBySize: function()
	{
		// get the value of the select input (size)
		var sizeId = $('#size').val();

		// only show the rows that belong to the selected standard. Hide the others (accept the columnheaders row).
		$('table.dataGrid tr[data-standard="' + sizeId + '"]').show();
		$('table.dataGrid tr').not('[data-standard="' + sizeId + '"]').not('tr:first').hide();

		// redo odd-even
		jsBackend.banners.controls.redoOddEven();
	},

	// redo odd-even
	redoOddEven: function()
	{
		// get the table
		var table = $('table.datagrid');

		// remove the odd and even class
		table.find('tr:visible').removeClass('odd').removeClass('even');

		// add even or odd
		table.find('tr:visible:even').addClass('even');
		table.find('tr:visible:odd').addClass('odd');
	},

	// uncheck checked checkboxes
	uncheckChecked: function()
	{
		$('table.datagrid input[type=checkbox]:checked').each(function()
		{
			// uncheck checkbox
			$(this).removeAttr('checked');

			// remove the 'selected' class from the parent row
			$(this).parent().parent().parent().removeClass('selected');
		});
	}
}

jsBackend.banners.settings =
{
	maxRowId: 0,

	// init, something like a constructor
	init: function()
	{
		if(jsBackend.current.action == 'settings')
		{
			// find max row id
			$('tr.sizeRow').each(function()
			{
				if($(this).data('size-id') > jsBackend.banners.settings.maxRowId)
				{
					jsBackend.banners.settings.maxRowId = $(this).data('size-id');
				}
			});

			// add a size
			$(document).on('click', 'a#addSize', function(evt)
			{
				// prevent default
				evt.preventDefault();

				// get new row id
				newId = jsBackend.banners.settings.maxRowId++;

				// build a new table
				var newRowHtml = $('tr[data-size-id=dummy]').html();
				newRowHtml = utils.string.replaceAll(newRowHtml, 'dummy', newId);
				newRowHtml = utils.string.replaceAll(newRowHtml, 'Dummy', newId);

				// add a row
				$('tr[data-size-id=0]').before('<tr class="sizeRow" data-size-id="' + newId + '">' + newRowHtml + '</tr>');

				// set correct data
				var newRow = $('tr[data-size-id=' + newId + ']');
				newRow.find('input[name="size[' + newId + ']"]').val($('input[name="size[0]"]').val());
				newRow.find('input[name="size_width[' + newId + ']"]').val($('input[name="size_width[0]"]').val());
				newRow.find('input[name="size_height[' + newId + ']"]').val($('input[name="size_height[0]"]').val());

				//  reset adder row and show new row
				newRow.show();
				$('tr[data-size-id=0]').removeClass('selected');
				$('input[name="size[0]"]').val('');
				$('input[name="size_width[0]"]').val('');
				$('input[name="size_height[0]"]').val('');
			});

			// delete a size
			$(document).on('click', 'a.deleteSize', function(evt)
			{
				// prevent default
				evt.preventDefault();

				// set new row id
				jsBackend.banners.settings.maxRowId--;

				// delete a row
				$(this).parentsUntil('tbody').remove();
			});
		}
	}
}

$(jsBackend.banners.init);