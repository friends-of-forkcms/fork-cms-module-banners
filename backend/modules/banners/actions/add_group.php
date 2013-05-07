<?php

/**
 * This is the add-action, it will display a form to create a banner group
 *
 * @author Lowie Benoot <lowiebenoot@netlash.com>
 */
class BackendBannersAddGroup extends BackendBaseActionAdd
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse the datagrid
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Load the form
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add');

		// create elements
		$this->frm->addText('name');
		$this->frm->addDropdown('size', BackendBannersModel::getStandardsForDropdown(false));

		// load datagrid
		$this->dgBanners = new BackendDataGridDB(BackendBannersModel::QRY_DATAGRID_BROWSE_BANNERS, BL::getWorkingLanguage());

		// hide column
		$this->dgBanners->setColumnsHidden(array('standard_id'));

		// change date format
		$this->dgBanners->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[date_from]'), 'date_from', true);
		$this->dgBanners->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[date_till]'), 'date_till', true);

		// add checkboxes
		$this->dgBanners->setMassActionCheckboxes('checkbox', '[id]');

		// add standard_id to each column
		$this->dgBanners->setRowAttributes(array('data-standard' => '[standard_id]'));

		// disable paging
		$this->dgBanners->setPaging(false);
	}

	/**
	 * Parse the form
	 */
	protected function parse()
	{
		// call parent
		parent::parse();

		// parse the datagrid for the drafts
		$this->tpl->assign('dgBanners', ($this->dgBanners->getNumResults() != 0) ? $this->dgBanners->getContent() : false);
	}

	/**
	 * Validate the form
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// get the selected banners.
			// this is done before cleaning up the fields, because the mass action checkboxes aren't added to the form
			$banners = SpoonFilter::getPostValue('id', null, null, 'array');

			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));

			// no banners selected?
			if(empty($banners))
			{
				// add form error
				$this->frm->addError('no banners selected');

				// assign form error in tpl
				$this->tpl->assign('formErrors', BL::err('SelectAtLeastOneBanner'));
			}

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['standard_id'] = (int) $this->frm->getField('size')->getValue();
				$item['language'] = BL::getWorkingLanguage();

				// insert group in db
				$item['id'] = BackendBannersModel::insertGroup($item);

				// put the selected banners in the groups
				BackendBannersModel::insertBannersInGroup($item['id'], $banners, $item['standard_id']);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_group', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('groups') . '&report=addedGroup&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}
