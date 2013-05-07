<?php

/**
 * In this file we store all generic functions that we will be using in the banners module
 *
 * @author Lowie Benoot <lowiebenoot@netlash.com>
 */
class BackendBannersModel
{
	const QRY_DATAGRID_BROWSE_BANNERS =
		'SELECT i.id, i.name, UNIX_TIMESTAMP(i.date_from) as date_from,
		 UNIX_TIMESTAMP(i.date_till) as date_till, i.num_clicks, i.num_views, standard_id
		 FROM banners AS i
		 WHERE i.language = ?';

	const QRY_DATAGRID_BROWSE_BANNERS_BY_STANDARD =
		'SELECT i.id, i.name, UNIX_TIMESTAMP(i.date_from) as date_from,
		 UNIX_TIMESTAMP(i.date_till) as date_till, i.num_clicks, i.num_views, standard_id
		 FROM banners AS i
		 WHERE i.standard_id = ? AND i.language = ?';

	const QRY_DATAGRID_BROWSE_BANNERS_GROUPS =
		'SELECT i.id, i.name, i.standard_id, bs.name AS size, bs.width, bs.height
		 FROM banners_groups AS i
		 INNER JOIN banners_standards AS bs ON bs.id = i.standard_id
		 WHERE i.language = ?';

	/**
	 * Deletes one or more items
	 *
	 * @param  mixed $ids The ids to delete.
	 */
	public static function delete($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// loop and cast to integers
		foreach($ids as &$id) $id = (int) $id;

		// create an array with an equal amount of questionmarks as ids provided
		$idPlaceHolders = array_fill(0, count($ids), '?');

		// get db
		$db = BackendModel::getContainer()->get('database');

		// get extra's to delete
		$extraIds = $db->getColumn(
			'SELECT extra_id
			 FROM banners
			 WHERE id IN (' . implode(',', $idPlaceHolders) . ')',
			$ids
		);

		// loop and cast to integers
		foreach($extraIds as &$extraId) $extraId = (int) $extraId;

		// create an array with an equal amount of questionmarks as ids provided
		$extraIdPlaceHolders = array_fill(0, count($extraIds), '?');

		// build extra
		$extra = array(
			'module' => 'banners',
			'type' => 'widget',
			'action' => 'index'
		);

		// delete extra's
		$db ->delete(
			'modules_extras',
			'id IN (' . implode(',', $extraIdPlaceHolders) . ') AND module = ? AND type = ? AND action = ?',
			array_merge($extraIds, array($extra['module'], $extra['type'], $extra['action']))
		);

		// delete records
		$db->delete(
			'banners',
			'id IN (' . implode(',', $idPlaceHolders) . ')',
			$ids
		);
		$db->delete(
			'banners_groups_members',
			'banner_id IN (' . implode(',', $idPlaceHolders) . ')',
			$ids
		);
	}

	/**
	 * Deletes one or more items
	 *
	 * @param  mixed $ids The ids to delete.
	 */
	public static function deleteGroup($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// loop and cast to integers
		foreach($ids as &$id) $id = (int) $id;

		// create an array with an equal amount of questionmarks as ids provided
		$idPlaceHolders = array_fill(0, count($ids), '?');

		// get db
		$db = BackendModel::getContainer()->get('database');

		// get extra's to delete
		$extraIds = $db->getColumn(
			'SELECT extra_id
			 FROM banners_groups
			 WHERE id IN (' . implode(',', $idPlaceHolders) . ')',
			$ids
		);

		// loop and cast to integers
		foreach($extraIds as &$extraId) $extraId = (int) $extraId;

		// create an array with an equal amount of questionmarks as ids provided
		$extraIdPlaceHolders = array_fill(0, count($extraIds), '?');

		// build extra
		$extra = array(
			'module' => 'banners',
			'type' => 'widget',
			'action' => 'index'
		);

		// delete extra's
		$db ->delete(
			'modules_extras',
			'id IN (' . implode(',', $extraIdPlaceHolders) . ') AND module = ? AND type = ? AND action = ?',
			array_merge($extraIds, array($extra['module'], $extra['type'], $extra['action']))
		);

		// delete records
		$db->delete(
			'banners_groups',
			'id IN (' . implode(',', $idPlaceHolders) . ')',
			$ids
		);
		$db->delete(
			'banners_groups_members',
			'group_id IN (' . implode(',', $idPlaceHolders) . ')',
			$ids
		);
	}

	/**
	 * Checks if a banner exists
	 *
	 * @param int $id The id of the banner to check for existence.
	 * @return bool
	 */
	public static function exists($id)
	{
		return (bool) BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(id)
			 FROM banners AS i
			 WHERE i.id = ?',
			array((int) $id)
		);
	}

	/**
	 * Checks if a group exists
	 *
	 * @param int $id The id of the group to check for existence.
	 * @return bool
	 */
	public static function existsGroup($id)
	{
		return (bool) BackendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(id)
			 FROM banners_groups AS i
			 WHERE i.id = ?',
			array((int) $id)
		);
	}

	/**
	 * Gets a banner by id
	 *
	 * @param int $id The id of the banner to get.
	 * @return array
	 */
	public static function get($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT i.*, UNIX_TIMESTAMP(i.date_from) AS date_from, UNIX_TIMESTAMP(i.date_till) AS date_till
			 FROM banners AS i
			 WHERE i.id = ?',
			array((int) $id)
		);
	}

	/**
	 * Returns the default group IDs
	 *
	 * @return array
	 */
	public static function getBanners()
	{
		return (array) BackendModel::getContainer()->get('database')->getColumn(
			'SELECT i.id
			 FROM banners AS i'
		);
	}

	/**
	 * Returns the default group IDs
	 *
	 * @param int $id The id of the standard.
	 * @return array
	 */
	public static function getBannersIDsByStandard($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getColumn(
			'SELECT i.id
			 FROM banners AS i
			 WHERE i.standard_id = ?',
			array((int) $id)
		);
	}

	/**
	 * Gets a group by id
	 *
	 * @param int $id The id of the group to get.
	 * @return array
	 */
	public static function getGroup($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT i.*
			 FROM banners_groups AS i
			 WHERE i.id = ?',
			array((int) $id)
		);
	}

	/**
	 * Gets the 'members' of a group
	 *
	 * @param int $id The id of the group.
	 * @return array
	 */
	public static function getGroupMembers($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getColumn(
			'SELECT i.banner_id
			 FROM banners_groups_members AS i
			 WHERE i.group_id = ?',
			array((int) $id)
		);
	}

	/**
	 * Gets the groups where a banner is member of
	 *
	 * @param int $id The id of the banner.
	 * @return array
	 */
	public static function getGroupsByBanner($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecords(
			'SELECT g.*
			 FROM banners_groups AS g
			 INNER JOIN banners_groups_members as m ON m.group_id = g.id
			 WHERE m.banner_id = ?',
			array((int) $id)
		);
	}

	/**
	 * Returns the banner standards (sizes)
	 *
	 * @param int $id the id of the standard.
	 * @return array
	 */
	public static function getStandard($id)
	{
		return (array) BackendModel::getContainer()->get('database')->getRecord(
			'SELECT i.*
			 FROM banners_standards AS i
			 WHERE i.id = ?',
			array((int) $id)
		);
	}

	/**
	 * Returns the banner standards (sizes)
	 *
	 * @return array
	 */
	public static function getStandards()
	{
		// get the standards
		$standards = (array) BackendModel::getContainer()->get('database')->getRecords(
			'SELECT i.*
			 FROM banners_standards AS i',
			array(),
			'id'
		);

		// loop standard sizes
		foreach($standards as $id => &$size)
		{
			// get banners to check if the size is editable
			$size['editable'] = (BackendBannersModel::getBannersIDsByStandard($id) == array());
		}

		// no sizes? create a default one
		if(empty($standards)) $standards[1] = array(
			'id' => 1,
			'name' => 'default',
			'width' => 100,
			'height' => 100
		);

		// return the standard sizes
		return $standards;
	}

	/**
	 * Returns the banner standards (sizes)
	 *
	 * @param bool[optional] $getEmpty Should we also get the empty sizes? (no banners with that size).
	 * @return array
	 */
	public static function getStandardsForDropdown($getEmpty = true)
	{
		// get empty standards?
		if($getEmpty) return (array) BackendModel::getContainer()->get('database')->getPairs(
			'SELECT i.id, CONCAT(i.name, " - ", i.width, "x", i.height)
			 FROM banners_standards AS i'
		);

		// don't get the empty standards
		else return (array) BackendModel::getContainer()->get('database')->getPairs(
			'SELECT DISTINCT i.id, CONCAT(i.name, " - ", i.width, "x", i.height)
			 FROM banners_standards AS i
			 INNER JOIN banners AS b ON i.id = b.standard_id'
		);
	}

	/**
	 * Inserts a banner into the database
	 *
	 * @param array $item The data to insert.
	 * @return int
	 */
	public static function insert(array $item)
	{
		// get db
		$db = BackendModel::getContainer()->get('database');

		// build extra
		$extra = array(
			'module' => 'banners',
			'type' => 'widget',
			'label' => 'BannerExtraLabel',
			'action' => 'index',
			'data' => null,
			'hidden' => 'N',
			'sequence' => $db->getVar(
				'SELECT MAX(i.sequence) + 1
				 FROM modules_extras AS i
				 WHERE i.module = ?',
				array('banners')
			)
		);

		if(is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
			'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
			 FROM modules_extras AS i'
		);

		// insert extra
		$item['extra_id'] = $db->insert('modules_extras', $extra);
		$extra['id'] = $item['extra_id'];

		// insert and return the new id
		$item['id'] = $db->insert('banners', $item);

		// update extra (item id is now known)
		$extra['data'] = serialize(array(
			'label_variables' => array($item['name']),
			'id' => $item['id'],
			'language' => $item['language'],
			'source' => 'banner',
			'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id'])
		);
		$db->update(
			'modules_extras',
			$extra,
			'id = ? AND module = ? AND type = ? AND action = ?',
			array($extra['id'], $extra['module'], $extra['type'], $extra['action'])
		);

		// return banner id
		return $item['id'];
	}

	/**
	 * Inserts a banner into the database
	 *
	 * @param int $groupId The id of the group.
	 * @param array $bannerIDs The IDs of the banners.
	 * @param int $standardId The id of the banner standard.
	 */
	public static function insertBannersInGroup($groupId, $bannerIDs, $standardId)
	{
		// redefine params
		$groupId = (int) $groupId;
		$standardId = (int) $standardId;
		$bannersIDs = (array) $bannerIDs;

		// get db instance
		$db = BackendModel::getContainer()->get('database');

		// get the allowed banners for this standard
		$allowedBanners = BackendBannersModel::getBannersIDsByStandard($standardId);

		// loop banners
		foreach($bannersIDs as $bannerID)
		{
			// make banner member of group, if allowed
			if(in_array($bannerID, $allowedBanners))
			{
				$db->insert(
					'banners_groups_members',
					array(
						'group_id' => $groupId,
						'banner_id' => (int) $bannerID
					)
				);
			}
		}
	}

	/**
	 * Inserts a banner group into the database
	 *
	 * @param array $item The data to insert.
	 * @return int
	 */
	public static function insertGroup(array $item)
	{
		// get db
		$db = BackendModel::getContainer()->get('database');

		// build extra
		$extra = array(
			'module' => 'banners',
			'type' => 'widget',
			'label' => 'GroupExtraLabel',
			'action' => 'index',
			'data' => null,
			'hidden' => 'N',
			'sequence' => $db->getVar(
				'SELECT MAX(i.sequence) + 1
				 FROM modules_extras AS i
				 WHERE i.module = ?',
				array('banners')
			)
		);

		if(is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
			'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
			 FROM modules_extras AS i'
		);

		// insert extra
		$item['extra_id'] = $db->insert('modules_extras', $extra);
		$extra['id'] = $item['extra_id'];

		// insert and return the new id
		$item['id'] = $db->insert('banners_groups', $item);

		// update extra (item id is now known)
		$extra['data'] = serialize(array(
			'label_variables' => array($item['name']),
			'id' => $item['id'],
			'language' => $item['language'],
			'source' => 'group',
			'edit_url' => BackendModel::createURLForAction('edit_group') . '&id=' . $item['id'])
		);
		$db->update(
			'modules_extras',
			$extra,
			'id = ? AND module = ? AND type = ? AND action = ?',
			array($extra['id'], $extra['module'], $extra['type'], $extra['action'])
		);

		// return the group id
		return $item['id'];
	}

	/**
	 * Checks if a banners is the only member of a group
	 *
	 * @param int $id The id of the banner.
	 * @return bool
	 */
	public static function isOnlyMemberOfAGroup($id)
	{
		// get the number of members from the groups, where this banner is member of
		$counts =  (array) BackendModel::getContainer()->get('database')->getColumn(
			'SELECT COUNT(m.id) FROM banners_groups_members AS m
			 INNER JOIN banners_groups_members AS m2 ON m.group_id = m2.group_id
			 WHERE m2.banner_id = ?
			 GROUP BY m.group_id',
			array((int) $id)
		);

		// if the minimum = 1, the banner is the only member of a group
		return empty($counts) ? false : min($counts) == 1;
	}

	/**
	 * sets (insert/delete) banners group members
	 *
	 * @param int $id The id of the group.
	 * @param array $banners The members.
	 */
	public static function setGroupMembers($id, array $banners)
	{
		// get db instance
		$db = BackendModel::getContainer()->get('database');

		// get the current members
		$members = self::getGroupMembers($id);

		// get the banners to delete (unchecked banners)
		$membersToDelete = array_diff($members, $banners);

		// get the members to insert
		$membersToInsert = array_diff($banners, $members);

		// delete the 'member rights' of the members to delete
		foreach($membersToDelete as $m)
		{
			$db->delete(
				'banners_groups_members',
				'banner_id = ? AND group_id = ?',
				array($m, $id)
			);
		}

		// insert the 'member rights' of the members to insert
		foreach($membersToInsert as $m) $db->insert('banners_groups_members', array('banner_id' => $m, 'group_id' => $id));
	}

	/**
	 * Updates a banner
	 *
	 * @param array $item The item.
	 * @return int
	 */
	public static function update(array $item)
	{
		// get db
		$db = BackendModel::getContainer()->get('database');

		// build extra
		$extra = array(
			'id' => $item['extra_id'],
			'module' => 'banners',
			'type' => 'widget',
			'label' => 'BannerExtraLabel',
			'action' => 'index',
			'data' => serialize(array(
				'label_variables' => array($item['name']),
				'id' => $item['id'],
				'language' => $item['language'],
				'source' => 'banner',
				'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id'])
				),
			'hidden' => 'N');

		// update extra
		$db->update('modules_extras', $extra, 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

		// update banner and return
		return $db->update('banners', $item, 'id = ?', array((int) $item['id']));
	}

	/**
	 * Updates a banner group
	 *
	 * @param array $item The item.
	 * @return int
	 */
	public static function updateGroup(array $item)
	{
		// get db
		$db = BackendModel::getContainer()->get('database');

		// build extra
		$extra = array(
			'id' => $item['extra_id'],
			'module' => 'banners',
			'type' => 'widget',
			'label' => 'GroupExtraLabel',
			'action' => 'index',
			'data' => serialize(array(
				'label_variables' => array($item['name']),
				'id' => $item['id'],
				'language' => $item['language'],
				'source' => 'group',
				'edit_url' => BackendModel::createURLForAction('edit_group') . '&id=' . $item['id'])
				),
			'hidden' => 'N');

		// update extra
		$db->update('modules_extras', $extra, 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

		// insert in db and return
		return $db->update('banners_groups', $item, 'id = ?', (int) $item['id']);
	}

	/**
	 * Updates the standard sizes
	 *
	 * @param array $sizes The sizes to insert.
	 * @return int
	 */
	public static function updateSizes(array $sizes)
	{
		// get db
		$db = BackendModel::getContainer()->get('database');

		// remove old sizes
		$db->truncate('banners_standards');

		// insert in db and return
		return $db->insert('banners_standards', $sizes);
	}
}
