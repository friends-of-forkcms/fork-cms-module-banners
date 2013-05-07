<?php

/**
 * This is the settings-action, it will display a form to set general banners settings
 *
 * @author Annelies Van Extergem <annelies.vanextergem@netlash.com>
 */
class BackendBannersSettings extends BackendBaseActionEdit
{
	/**
	 * The standard sizes, sent and original.
	 *
	 * @var	array
	 */
	private $sizes = array(), $originalSizes = array();

	/**
	 * Execute the action
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// get data
		$this->getData();

		// load form
		$this->loadForm();

		// validates the form
		$this->validateForm();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Get the data to use in the form
	 */
	private function getData()
	{
		// get the banner standard sizes
		$this->originalSizes = BackendBannersModel::getStandards();

		// get info sent through the form
		$sentSizes = SpoonFilter::getPostValue('size', null, array(), 'array');
		$sentSizeWidths = SpoonFilter::getPostValue('size_width', null, array(), 'array');
		$sentSizeHeights = SpoonFilter::getPostValue('size_height', null, array(), 'array');

		// the form has been sent so overwrite the sizes
		if(!empty($sentSizes))
		{
			// reset sizes
			$this->sizes = array();

			// loop sent sizes
			foreach($sentSizes as $sentId => $sentSize)
			{
				// don't empty rows and dummy row
				if(($sentSize == '' && $sentSizeWidths[$sentId] == '' && $sentSizeHeights[$sentId] == '') || $sentId === 'dummy') continue;

				// init size
				$size = array();

				// get banners to check if the size is editable
				$size['editable'] = (BackendBannersModel::getBannersIDsByStandard($sentId) == array());

				// set values
				$size['id'] = ($sentId == 0 ? max(array_keys($sentSizes)) + 1 : $sentId);
				$size['name'] = $sentSize;
				$size['width'] = ($size['editable'] ? $sentSizeWidths[$sentId] : $this->originalSizes[$sentId]['width']);
				$size['height'] = ($size['editable'] ? $sentSizeHeights[$sentId] : $this->originalSizes[$sentId]['height']);

				// add size to sizes array
				$this->sizes[] = $size;
			}

			// no sizes? make a default one
			if(empty($this->sizes)) $this->sizes[1] = array('id' => 1, 'name' => 'default', 'width' => 100, 'height' => 100);
		}

		// the form has not been sent so work with the original sizes
		else $this->sizes = array_values($this->originalSizes);
	}

	/**
	 * Loads the settings form
	 */
	private function loadForm()
	{
		// init settings form
		$this->frm = new BackendForm('settings');

		// init vars
		$maxIndex = count($this->sizes) - 1;

		// loop the sizes
		foreach($this->sizes as $i => &$size)
		{
			// set variables to use in the template
			if($i > 0) $size['delete'] = true;

			// make the form elements
			$size['formElements']['txtSize'] = $this->frm->addText('size[' . $size['id'] . ']', $size['name']);
			$size['formElements']['txtSizeWidth'] = $this->frm->addText('size_width[' . $size['id'] . ']', $size['width']);
			$size['formElements']['txtSizeHeight'] = $this->frm->addText('size_height[' . $size['id'] . ']', $size['height']);

			// limit editing possibilities if there are banners for this size
			if(!$size['editable'])
			{
				$size['delete'] = false;
				$size['hasBanners'] = true;
				$this->frm->getField('size_width[' . $size['id'] . ']')->setAttribute('disabled', 'disabled');
				$this->frm->getField('size_height[' . $size['id'] . ']')->setAttribute('disabled', 'disabled');
			}
		}

		// set default size fields for adder row
		$adderSize = array();
		$adderSize['id'] = 0;
		$adderSize['add'] = true;
		$adderSize['formElements']['txtSize'] = $this->frm->addText('size[0]');
		$adderSize['formElements']['txtSizeWidth'] = $this->frm->addText('size_width[0]');
		$adderSize['formElements']['txtSizeHeight'] = $this->frm->addText('size_height[0]');

		// add to the sizes array
		$this->sizes[] = $adderSize;

		// set default size fields for dummy row
		$dummySize = array();
		$dummySize['id'] = 'dummy';
		$dummySize['delete'] = true;
		$dummySize['hidden'] = true;
		$dummySize['formElements']['txtSize'] = $this->frm->addText('size[dummy]');
		$dummySize['formElements']['txtSizeWidth'] = $this->frm->addText('size_width[dummy]');
		$dummySize['formElements']['txtSizeHeight'] = $this->frm->addText('size_height[dummy]');

		// add to the sizes array
		$this->sizes[] = $dummySize;
	}

	/**
	 * Parse the form
	 */
	protected function parse()
	{
		// parse the sizes
		$this->tpl->assign('sizes', $this->sizes);

		// parse the form
		$this->frm->parse($this->tpl);
	}

	/**
	 * Validates the settings form
	 */
	private function validateForm()
	{
		// form is submitted
		if($this->frm->isSubmitted())
		{
			// loop sizes
			foreach($this->sizes as $size)
			{
				// don't look at adder and dummy row
				if($size['id'] == 0 || $size['id'] == 'dummy') continue;

				// validate fields
				if($size['name'] == '') $size['formElements']['txtSize']->addError(BL::getError('FieldIsRequired'));
				if(!SpoonFilter::isNumeric($size['width'])) $size['formElements']['txtSizeWidth']->addError(BL::getError('InvalidNumber'));
				if(!SpoonFilter::isNumeric($size['height'])) $size['formElements']['txtSizeHeight']->addError(BL::getError('InvalidNumber'));
			}

			// form is validated
			if($this->frm->isCorrect())
			{
				// init vars
				$insertSizes = array();

				// loop sizes
				foreach($this->sizes as $size)
				{
					// don't insert adder and dummy row
					if($size['id'] == 0 || $size['id'] == 'dummy') continue;

					// build insert array
					$insertSize = array();
					$insertSize['id'] = $size['id'];
					$insertSize['name'] = $size['name'];
					$insertSize['width'] = ($size['editable'] ? $size['width'] : $this->originalSizes[$size['id']]['width']);
					$insertSize['height'] = ($size['editable'] ? $size['height'] : $this->originalSizes[$size['id']]['height']);

					// add to insert array
					$insertSizes[] = $insertSize;
				}

				// update sizes
				BackendBannersModel::updateSizes($insertSizes);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

				// redirect to the settings page
				$this->redirect(BackendModel::createURLForAction('settings') . '&report=saved');
			}
		}
	}
}
