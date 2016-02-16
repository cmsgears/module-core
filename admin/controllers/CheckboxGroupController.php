<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class CheckboxGroupController extends \cmsgears\core\admin\controllers\base\DropdownController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'checkbox-group' ];

		$this->type 	= CoreGlobal::TYPE_CHECKBOX_GROUP;
		$this->title 	= 'Checkbox Group';
	}

	// Instance Methods --------------------------------------------

	// DropdownController --------------------

	public function actionAll() {

		Url::remember( [ 'checkbox-group/all' ], 'categories' );

		return parent::actionAll();
	}
}

?>