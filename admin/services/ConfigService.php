<?php
namespace cmsgears\modules\core\admin\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\core\common\models\entities\Config;

class ConfigService extends \cmsgears\modules\core\common\services\ConfigService {
	
	// Static Methods ----------------------------------------------

	// Update

	public static function update(  $modle, $id ) {

		$configUpdate	= Config::findById( $id );

		if( strcmp( $configUpdate->getFieldType(), "password" ) == 0 ) {

			$configUpdate->setValue( Yii::$app->security->generatePasswordHash( $modle->getValue() ) );
		}
		else {

			$configUpdate->setValue( $modle->getValue() );
		}

		$configUpdate->save();

		return true;
	}
}

?>