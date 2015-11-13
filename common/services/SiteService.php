<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Site;

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
	 * @param string $name
	 * @param string $type
	 * @return array - An array of site meta for the given site name and meta type.
	 */
    public static function getMetaByNameType( $name, $type ) {

		$site = Site::findByName( $name );

		return $site->getModelMetasByType( $type );
    }

	/**
	 * @param string $name
	 * @param string $type
	 * @return array - An associative array of site meta for the given site name and meta type having name as key and value as value.
	 */
    public static function getMetaMapByNameType( $name, $type ) {

		$site = Site::findByName( $name );

		return $site->getMetaNameValueMapByType( $type );
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

		if( isset( $avatar ) ) {

			FileService::saveImage( $avatar, [ 'model' => $site, 'attribute' => 'avatarId' ] );
		}

		if( isset( $banner ) ) {

			FileService::saveImage( $banner, [ 'model' => $site, 'attribute' => 'bannerId' ] );
		}

		$site->save();

		return $site;
	}

	// Update --------------

	public static function update( $site, $avatar = null, $banner = null ) {

		$siteToUpdate	= self::findById( $site->id );

		$siteToUpdate->copyForUpdateFrom( $site, [ 'avatarId', 'name', 'order' ] );

		if( isset( $avatar ) ) {

			FileService::saveImage( $avatar, [ 'model' => $siteToUpdate, 'attribute' => 'avatarId' ] );
		}

		if( isset( $banner ) ) {

			FileService::saveImage( $banner, [ 'model' => $siteToUpdate, 'attribute' => 'bannerId' ] );
		}

		$siteToUpdate->update();

		return $siteToUpdate;
	}

	public static function updateMeta( $meta ) {

		$site 			= Site::findByName( Yii::$app->cmgCore->getSiteName() );		
		$metaToUpdate	= $site->getModelMetaByTypeName( $meta->type, $meta->name );

		$metaToUpdate->copyForUpdateFrom( $meta, [ 'value' ] );

		$metaToUpdate->update();

		return $metaToUpdate;
	}

	// Delete -----------

	/**
	 * @param Site $site
	 * @return boolean
	 */
	public static function delete( $site ) {

		// Find existing Site
		$siteToDelete	= self::findById( $site->id );

		// Delete Site
		$siteToDelete->delete();

		return true;
	}
}

?>