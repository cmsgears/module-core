<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class FileController extends \cmsgears\core\common\controllers\apix\FileController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->enableCsrfValidation = false;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

	public function behaviors() {

        $behaviors  = parent::behaviors();
        
        $behaviors[ 'rbac' ][ 'actions' ]   = [ 'fileHandler' => [ 'permission' => CoreGlobal::PERM_ADMIN ] ];
        
        return $behaviors;
    }
}

?>