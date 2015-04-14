<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Config;

class ConfigService extends Service {

	// Static Methods ----------------------------------------------

	// Read

	public static function findByType( $type ) {

		return Config::findByType( $type );
    }

    public static function getNameValueMapByType( $type ) {

		return self::findNameValueMap( "name", "value", CoreTables::TABLE_CONFIG, [ "type" => $type ] );
    }
}

?>