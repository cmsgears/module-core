<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Config;

class ConfigService extends \cmsgears\core\common\services\ConfigService {
	
	// Static Methods ----------------------------------------------

	// Update

	public static function update( $config ) {
		
		// Find existing Config
		$configUpdate	= Config::findById( $config->id );

		if( strcmp( $configUpdate->fieldType, "password" ) == 0 ) {

			$configUpdate->value = Yii::$app->security->generatePasswordHash( $config->value );
		}
		else {

			$configUpdate->copyForUpdateFrom( $config, [ 'value' ] );
		}

		$configUpdate->save();

		return true;
	}
}

?>