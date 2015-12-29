<?php
namespace cmsgears\core\admin\controllers\dropdown;

// Yii Imports
use \Yii;  
use yii\helpers\Url;

// CMG Imports 

class OptionController extends \cmsgears\core\admin\controllers\base\category\OptionController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'dropdown' ];
	}

	// Instance Methods ------------------

	// OptionController

	public function actionAll( $id ) {
		 
		Url::remember( [ "dropdown/option/all?id=$id" ], 'options' );
		
		return parent::actionAll( $id );
	}
}

?>