<?php
namespace cmsgears\core\admin\controllers\newsletter;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 		= [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter-template' ];
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
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

	public function actionAll() {
		
		Url::remember( [ 'newsletter/template/all' ], 'templates' );

		return parent::actionAll( CoreGlobal::TYPE_NEWSLETTER );
	}
	
	public function actionCreate() {

		return parent::actionCreate( CoreGlobal::TYPE_NEWSLETTER );
	}
	 
	public function actionUpdate( $id ) {

		return parent::actionUpdate( $id, CoreGlobal::TYPE_NEWSLETTER );
	}
	
	public function actionDelete( $id ) {

		return parent::actionDelete( $id, CoreGlobal::TYPE_NEWSLETTER );
	}
}

?>