<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

/**
 * Useful for models which need submitted data for special processing.
 */
abstract class FormModel extends \cmsgears\core\common\models\forms\JsonModel {

	public $submittedData;

    public function load( $data, $formName = null ) {

		if( isset( $formName ) && isset( $data[ $formName ] ) ) {

			$this->submittedData	= $data[ $formName ];
		}

		return parent::load( $data, $formName );
    }
}

?>