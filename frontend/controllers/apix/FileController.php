<?php
namespace cmsgears\core\frontend\controllers\apix;

class FileController extends \cmsgears\core\common\controllers\apix\FileController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->enableCsrfValidation = false;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component
}

?>