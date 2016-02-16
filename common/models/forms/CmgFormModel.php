<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

class CmgFormModel extends \yii\base\Model {

	public $submittedData;

    public function load( $data, $formName = null ) {

		if( isset( $formName ) && isset( $data[ $formName ] ) ) {

			$this->submittedData	= $data[ $formName ];
		}

		return parent::load( $data, $formName );
    }
}

?>