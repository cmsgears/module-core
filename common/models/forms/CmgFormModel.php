<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

/**
 * Useful for models which need submitted data for special processing.
 */
abstract class CmgFormModel extends \yii\base\Model {

	public $submittedData;

    public function load( $data, $formName = null ) {

		if( isset( $formName ) && isset( $data[ $formName ] ) ) {

			$this->submittedData	= $data[ $formName ];
		}

		return parent::load( $data, $formName );
    }
}

?>