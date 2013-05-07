<?php

/**
 * This action will delete a banner group
 *
 * @author Lowie Benoot <lowie.benoot@netlash.com>
 */
class BackendBannersDeleteGroup extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendBannersModel::existsGroup($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get data
			$this->record = (array) BackendBannersModel::getGroup($this->id);

			// delete item
			BackendBannersModel::deleteGroup($this->id);

			// trigger event
			BackendModel::triggerEvent($this->getModule(), 'after_delete_group', array('item' => $this->record));

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('groups') . '&report=deletedGroup&var=' . urlencode($this->record['name']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('groups') . '&error=non-existing');
	}
}
