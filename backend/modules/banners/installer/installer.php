<?php

/**
 * Installer for the banners module
 *
 * @author Lowie Benoot <lowie.benoot@netlash.com>
 */
class BannersInstaller extends ModuleInstaller
{
	/**
	 * Install the module
	 */
	public function install()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// add 'blog' as a module
		$this->addModule('banners', 'The banners module.');

		// import locale
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');

		// module rights
		$this->setModuleRights(1, 'banners');

		// action rights
		$this->setActionRights(1, 'banners', 'add');
		$this->setActionRights(1, 'banners', 'add_group');
		$this->setActionRights(1, 'banners', 'delete');
		$this->setActionRights(1, 'banners', 'delete_group');
		$this->setActionRights(1, 'banners', 'edit');
		$this->setActionRights(1, 'banners', 'edit_group');
		$this->setActionRights(1, 'banners', 'groups');
		$this->setActionRights(1, 'banners', 'index');
		$this->setActionRights(1, 'banners', 'settings');

		// set navigation
		$navigationModulesId = $this->setNavigation(null, 'Modules');
		$bannerModuleId = $this->setNavigation($navigationModulesId, 'Banners');
		$this->setNavigation($bannerModuleId, 'Banners', 'banners/index', array('banners/add', 'banners/edit'));
		$this->setNavigation($bannerModuleId, 'Groups', 'banners/groups', array('banners/add_group', 'banners/edit_group'));

		// settings navigation
		$navigationSettingsId = $this->setNavigation(null, 'Settings');
		$navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
		$this->setNavigation($navigationModulesId, 'Banners', 'banners/settings');

		// create directory for the original files
		if(!SpoonDirectory::exists(PATH_WWW . '/frontend/files/banners/')) SpoonDirectory::create(PATH_WWW . '/frontend/files/banners/');

		// create directory for the original files
		if(!SpoonDirectory::exists(PATH_WWW . '/frontend/files/banners/original/')) SpoonDirectory::create(PATH_WWW . '/frontend/files/banners/original/');

		// create folder for resized images
		if(!SpoonDirectory::exists(PATH_WWW . '/frontend/files/banners/resized/')) SpoonDirectory::create(PATH_WWW . '/frontend/files/banners/resized/');
	}
}
