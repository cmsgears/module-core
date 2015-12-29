<?php
namespace cmsgears\core\admin\controllers\checkboxgroup;

// Yii Imports
use \Yii;  
use yii\helpers\Url;

// CMG Imports 

class OptionController extends \cmsgears\core\admin\controllers\base\category\OptionController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'checkbox-group' ];
	}

	// Instance Methods ------------------

	// OptionController

	public function actionAll( $id ) {
		 
		Url::remember( [ "checkboxgroup/option/all?id=$id" ], 'options' );
		
		return parent::actionAll( $id );
	}
}

?>