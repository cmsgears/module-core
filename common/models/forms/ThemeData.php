<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\base\Model;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ThemeData extends Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------
	
	public $css;

	// Instance Methods --------------------------------------------
	
	// yii\base\Model

	public function rules() {

        $rules = [
			[ [ 'css' ], 'safe' ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'css' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'css' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STYLE )
		];
	}
}

?>