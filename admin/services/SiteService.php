<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\ModelMeta;

class SiteService extends \cmsgears\core\common\services\SiteService {

	// Static Methods ----------------------------------------------

	// Create

	public static function create( $site ) {

		$site->create();

		return $site;
	}

	// Update

	public static function update( $site ) {

		$siteToUpdate	= self::findById( $site->id );

		$siteToUpdate->copyForUpdateFrom( $site, [ 'name' ] );

		$siteToUpdate->update();

		return $siteToUpdate;
	}

	public static function updateMeta( $meta ) {

		// Find Current Site
		$site 			= Site::findByName( Yii::$app->cmgCore->getSiteName() );		
		$metaToUpdate	= $site->getMetaByTypeName( $meta->type, $meta->name );

		$metaToUpdate->copyForUpdateFrom( $meta, [ 'value' ] );

		$metaToUpdate->update();

		return $metaToUpdate;
	}
}

?>