<?php
namespace cmsgears\modules\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\core\common\models\entities\CoreTables;
use cmsgears\modules\core\common\models\entities\Config;

class ConfigService extends Service {

	// Static Methods ----------------------------------------------

	// Read

	public static function findByType( $type ) {

		return Config::findByType( $type );
    }

    public static function getKeyValueMapByType( $type ) {

		return self::findKeyValueMap( "config_key", "config_value", CoreTables::TABLE_CONFIG, [ "config_type" => $type ] );
    }
}

?>