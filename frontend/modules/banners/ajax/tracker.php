<?php

/**
 * This is the tracker action.
 * It will increase the amount of clicks for a banner, and then redirect the user to the correct url.
 *
 * @author Lowie Benoot <lowie.benoot@netlash.com>
 */
class FrontendBannersAjaxTracker extends FrontendBaseAJAXAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// get parameters
		$id = SpoonFilter::getPostValue('id', null, '');

		// validate
		if($id == '') $this->output(self::BAD_REQUEST, null, 'id-parameter is missing.');

		// internal referrer?
		if(!SpoonFilter::isInternalReferrer(array(SITE_DOMAIN)) || !isset($_SERVER['HTTP_REFERER'])) $this->output(self::BAD_REQUEST, null, 'invalid referrer.');

		// banner exists?
		if(FrontendBannersModel::exists($id))
		{
			// add a click
			FrontendBannersModel::increaseNumClicks($id);
		}

		// output
		$this->output(self::OK);
	}
}
