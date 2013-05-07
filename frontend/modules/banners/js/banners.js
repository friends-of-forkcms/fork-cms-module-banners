/**
 * Interaction for the banners module
 *
 * @author	Lowie Benoot <lowiebenoot@netlash.com>
 */
jsFrontend.banners =
{
	// init, something like a constructor
	init: function()
	{
		jsFrontend.banners.controls.init();
	}
}

jsFrontend.banners.controls =
{
	// init, something like a constructor
	init: function()
	{
		$('a.bannerWidgetURL').on('click', function()
		{
			$banner = $(this);

			// ajax call!
			$.ajax(
			{
				data:
				{
					fork: { module: 'banners', action: 'tracker' },
					id: $banner.data('id')
				}
			});
		});
	}
}


$(jsFrontend.banners.init);