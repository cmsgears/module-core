<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\Theme;

/**
 * The class SiteService is base class to perform database activities for Site Entity.
 */
class SiteService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Site
	 */
	public static function findById( $id ) {

		return Site::findById( $id );
    }

	/**
	 * @param string $name
	 * @return Site
	 */
	public static function findByName( $name ) {

		return Site::findByName( $name );
    }

	/**
	 * @param string $name
	 * @return Site
	 */
	public static function findBySlug( $slug ) {

		return Site::findBySlug( $slug );
    }

	/**
	 * @param string $slug
	 * @param string $type
	 * @return array - An array of site attribute for the given site name and meta type.
	 */
    public static function getAttributeBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $site->getModelAttributesByType( $type );
    }

	/**
	 * @param string $name
	 * @param string $type
	 * @return array - An associative array of site attribute for the given site name and meta type having name as key and value as attribute.
	 */
    public static function getAttributeMapBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $site->getAttributeMapByType( $type );
    }

	/**
	 * @param string $name
	 * @param string $type
	 * @return array - An associative array of site attribute for the given site name and meta type having name as key and value as value.
	 */
    public static function getAttributeNameValueMapBySlugType( $slug, $type ) {

		$site = Site::findBySlug( $slug );

		return $site->getAttributeNameValueMapByType( $type );
    }

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Site(), $config );
	}

	// Create --------------

	public static function create( $site, $avatar = null, $banner = null ) {

		FileService::saveFiles( $site, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		$site->save();

		return $site;
	}

	// Update --------------

	public static function update( $site, $avatar = null, $banner = null ) {

		$siteToUpdate	= self::findById( $site->id );

		$siteToUpdate->copyForUpdateFrom( $site, [ 'themeId', 'name', 'order', 'active' ] );
		
		FileService::saveFiles( $siteToUpdate, [ 'avatarId' => $avatar, 'bannerId' => $banner ] );

		$siteToUpdate->update();

		return $siteToUpdate;
	}

	// Delete -----------

	/**
	 * @param Site $site
	 * @return boolean
	 */
	public static function delete( $site, $avatar = null, $banner = null ) {

		// Find existing Site
		$siteToDelete	= self::findById( $site->id );

		// Delete Site
		$siteToDelete->delete();

		FileService::deleteFiles( [ $avatar, $banner ] );

		return true;
	}
}

?>