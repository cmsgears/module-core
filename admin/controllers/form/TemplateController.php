<?php
namespace cmsgears\core\admin\controllers\form;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'  => [ 'get' ],
	                'create'  => [ 'get', 'post' ],
	                'update'  => [ 'get', 'post' ],
	                'delete'  => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// CategoryController --------------------

	public function actionAll( $type = null ) {

		Url::remember( [ 'page/template/all' ], 'templates' );

		return parent::actionAll( [ 'parent' => 'sidebar-form', 'child' => 'form-template' ], CoreGlobal::TYPE_FORM );
	}

	public function actionCreate() {

		return parent::actionCreate( [ 'parent' => 'sidebar-form', 'child' => 'form-template' ], CoreGlobal::TYPE_FORM );
	}

	public function actionUpdate( $id ) {

		return parent::actionUpdate( $id, [ 'parent' => 'sidebar-form', 'child' => 'form-template' ], CoreGlobal::TYPE_FORM );
	}

	public function actionDelete( $id ) {

		return parent::actionDelete( $id, [ 'parent' => 'sidebar-form', 'child' => 'form-template' ], CoreGlobal::TYPE_FORM );
	}
}

?>