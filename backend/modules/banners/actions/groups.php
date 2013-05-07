<?php

/**
 * This is the groups-action (default), it will display the overview of the groups
 *
 * @author Lowie Benoot <lowiebenoot@netlash.com>
 */
class BackendBannersGroups extends BackendBaseActionIndex
{
	/**
	 * Datagrids
	 *
	 * @var	SpoonDataGrid
	 */
	private $dgGroups;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load datagrid
		$this->loadDataGrids();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * returns a string that represents the label for the standars (example: skyscraper - 160x600)
	 *
	 * @param string $name the name of the standard.
	 * @param int $width the width of the standard.
	 * @param int $height the height of the standard.
	 * @return string
	 */
	public static function getStandardLabel($name, $width, $height)
	{
		return $name . ' - ' . $width . 'x' . $height;
	}

	/**
	 * Loads the datagrids for the blogposts
	 */
	private function loadDataGrids()
	{
		// create datagrid
		$this->dgGroups = new BackendDataGridDB(BackendBannersModel::QRY_DATAGRID_BROWSE_BANNERS_GROUPS, BL::getWorkingLanguage());

		// change the banner standard name value (include the width and height)
		$this->dgGroups->setColumnFunction(array(__CLASS__, 'getStandardLabel'), array('[size]', '[width]', '[height]'), 'size', true);

		// hide columns
		$this->dgGroups->setColumnsHidden(array('standard_id', 'height', 'width'));

		// set sorting columns
		$this->dgGroups->setSortingColumns(array('name', 'size'), 'id');
		$this->dgGroups->setSortParameter('DESC');

		// set colum URLs
		$this->dgGroups->setColumnURL('name', BackendModel::createURLForAction('edit_group') . '&amp;id=[id]');

		// add edit column
		$this->dgGroups->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_group') . '&amp;id=[id]', BL::lbl('Edit'));
	}

	/**
	 * Parse all datagrids
	 */
	protected function parse()
	{
		// parse the datagrid for the drafts
		$this->tpl->assign('dgGroups', ($this->dgGroups->getNumResults() != 0) ? $this->dgGroups->getContent() : false);
	}
}
