<?php

/**
 * FrontendBannersWidgetIndex
 * This is a widget for the header image
 *
 * @author Lowie Benoot <lowie.benoot@netlash.com>
 */
class FrontendBannersWidgetIndex extends FrontendBaseWidget
{
	/**
	 * Execute the extra
	 */
	public function execute()
	{
		// call parent
		parent::execute();

		// load template
		$this->loadTemplate();

		// parse
		$this->parse();

		// display
		return $this->tpl->getContent(FRONTEND_MODULES_PATH . '/' . $this->getModule() . '/layout/widgets/' . $this->getAction() . '.tpl');
	}

	/**
	 * Parse
	 */
	private function parse()
	{
		// get the banner if the widget is for a single banner
		if($this->data['source'] == 'banner') $banner = FrontendBannersModel::get((int) $this->data['id']);

		// get random banner from group if the widget is for a banner group
		else $banner = FrontendBannersModel::getRandomBannerForGroup((int) $this->data['id']);

		if(!empty($banner))
		{
			// build utm array
			$utm['utm_source'] = SpoonFilter::urlise(FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE, SITE_DEFAULT_TITLE));
			$utm['utm_medium'] = 'banner';
			$utm['utm_campaign'] = SpoonFilter::urlise(FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE, SITE_DEFAULT_TITLE));;

			// get parameters in url already
			if(strpos($banner['url'], '?') !== false) $glue = '&amp;';
			else $glue = '?';

			// add utm to url
			$banner['url'] .= $glue . http_build_query($utm, '', '&amp;');

			// assign item
			$this->tpl->assign('item', (array) $banner);

			// assign the tracker url
			$this->tpl->assign('trackerURL', '/frontend/ajax.php?module=banners&action=tracker&amp;language=' . FRONTEND_LANGUAGE);

			// is the file an swf?
			$isSWF = SpoonFile::getExtension($banner['file']) == 'swf';

			// assign a part of the microtime if it is an swf.
			// otherwise it isn't possible to add the same flashbanner more than once to a page because the id swfObject div would be the same.
			if($isSWF) $this->tpl->assign('microtime', substr(microtime(), 2, 8));

			// is the file an swf?
			$this->tpl->assign('isSWF', $isSWF);
		}

		// assign false, otherwise it can be assigned by another banner
		else $this->tpl->assign('item', false);
	}
}
