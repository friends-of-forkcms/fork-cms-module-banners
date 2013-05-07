<?php

/**
 * In this file we store all generic functions that we will be using in the banners module
 *
 * @author Lowie Benoot <lowiebenoot@netlash.com>
 */
class FrontendBannersModel
{
	/**
	 * Checks if a banner exists
	 *
	 * @param int $id The id of the banner to check for existence.
	 * @return bool
	 */
	public static function exists($id)
	{
		return (bool) FrontendModel::getContainer()->get('database')->getVar(
			'SELECT COUNT(id)
			 FROM banners AS i
			 WHERE i.id = ? AND
			 	(i.date_till >= NOW() OR i.date_till IS NULL) AND
				(i.date_from <= NOW() OR i.date_from IS NULL)',
			array((int) $id)
		);
	}

	/**
	 * Get an item
	 *
	 * @param int $id The id of the group.
	 * @return array
	 */
	public static function get($id)
	{
		// get db
		$db = FrontendModel::getContainer()->get('database');

		// get a random banner from the group
		$banner = (array) $db->getRecord(
			'SELECT b.id, b.name, b.file, b.url, s.width, s.height
			 FROM banners AS b
			 INNER JOIN banners_standards AS s ON s.id = b.standard_id
			 WHERE b.id = ? AND
			 	(b.date_till >= NOW() OR b.date_till IS NULL) AND
				(b.date_from <= NOW() OR b.date_from IS NULL)',
			array((int) $id)
		);

		// add a view for the banner
		if(!empty($banner)) self::increaseNumViews($id);

		// return the banner
		return $banner;
	}

	/**
	 * Get an item
	 *
	 * @param int $id The id of the group.
	 * @return array
	 */
	public static function getRandomBannerForGroup($id)
	{
		// get db
		$db = FrontendModel::getContainer()->get('database');

		// get a random banner from the group
		$banner = (array) $db->getRecords(
			'SELECT b.id, b.file, b.url, s.width, s.height
			 FROM banners_groups_members AS m
			 INNER JOIN banners AS b ON b.id = m.banner_id
			 INNER JOIN banners_groups AS g ON g.id = m.group_id
			 INNER JOIN banners_standards AS s on s.id = g.standard_id
			 WHERE m.group_id = ? AND
			 	(b.date_till >= NOW() OR b.date_till IS NULL) AND
			 	(b.date_from <= NOW() OR b.date_from IS NULL)',
			array((int) $id)
		);

		// get random value
		$banner = $banner[array_rand($banner)];

		// add a view for the banner
		if(!empty($banner)) self::increaseNumViews((int) $banner['id']);

		// return the banner
		return $banner;
	}

	/**
	 * Increase the number of clicks from a banner
	 *
	 * @param int $id The id of the banner.
	 */
	public static function increaseNumClicks($id)
	{
		// increase num clicks
		FrontendModel::getContainer()->get('database')->execute(
			'UPDATE banners AS b
			 SET b.num_clicks = b.num_clicks+1
			 WHERE b.id = ?',
			array((int) $id)
		);
	}

	/**
	 * Increase the number of views from a banner
	 *
	 * @param int $id The id of the banner.
	 */
	public static function increaseNumViews($id)
	{
		// increase num clicks
		FrontendModel::getContainer()->get('database')->execute(
			'UPDATE banners AS b
			 SET b.num_views = b.num_views+1
			 WHERE b.id = ?',
			array((int) $id)
		);
	}
}
