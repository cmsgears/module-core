<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Category;

use cmsgears\core\admin\services\CategoryService; 

class CheckboxGroupController extends \cmsgears\core\admin\controllers\base\CategoryController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'checkbox-group' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'index' ] 	= [ 'permission' => CoreGlobal::PERM_CORE ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ] 	= [ 'get' ];

		return $behaviours;
    }

	// DropdownController --------------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll( $type = null ) {

		Url::remember( [ 'checkbox-group/all' ], 'categories' );

		return parent::actionAll( CoreGlobal::TYPE_CHECKBOX_GROUP, 'Checkbox Group' );
	}

	public function actionCreate() {

		return parent::actionCreate( CoreGlobal::TYPE_CHECKBOX_GROUP, 'Checkbox Group' );
	}

	public function actionUpdate( $id ) {

		return parent::actionUpdate( $id, CoreGlobal::TYPE_CHECKBOX_GROUP, 'Checkbox Group' );
	}
	
	public function actionDelete( $id ) {

		return parent::actionDelete( $id, CoreGlobal::TYPE_CHECKBOX_GROUP, 'Checkbox Group' );
	}
}

?>