<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\admin\services\PermissionService;

use cmsgears\core\admin\controllers\BaseController;

use cmsgears\core\common\utilities\AjaxUtil;

class PermissionController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'bindRoles'  => [ 'permission' => CoreGlobal::PERM_IDENTITY_RBAC ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'bindRoles'  => ['post']
                ]
            ]
        ];
    }

	// PermissionController --------------

	public function actionBindRoles() {

		$binder = new Binder();

		if( $binder->load( Yii::$app->request->post(), "Binder" ) ) {

			if( PermissionService::bindRoles( $binder ) ) {

				// Trigger Ajax Success
				AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
        AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>