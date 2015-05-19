<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Site;

class SiteService extends Service {

	// Static Methods ----------------------------------------------

	// Read

	public static function findById( $id ) {

		return Site::findById( $id );
    }

	public static function findByName( $name ) {

		return Site::findByName( $name );
    }

    public static function getMetaByNameType( $name, $type ) {

		$site = Site::findByName( $name );

		return $site->getMetasByType( $type );
    }

    public static function getMetaMapByNameType( $name, $type ) {

		$site = Site::findByName( $name );

		return $site->getMetaNameValueMapByType( $type );
    }
}

?>