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
		$configToUpdate	= Config::findById( $config->id );

		if( strcmp( $configToUpdate->fieldType, "password" ) == 0 ) {

			$configToUpdate->value = Yii::$app->security->generatePasswordHash( $config->value );
		}
		else {

			$configToUpdate->copyForUpdateFrom( $config, [ 'value' ] );
		}

		$configToUpdate->update();

		return true;
	}
}

?>