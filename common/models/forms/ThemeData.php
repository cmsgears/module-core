<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ThemeData extends \yii\base\Model {

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