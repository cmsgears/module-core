<?php
namespace cmsgears\modules\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\common\models\entities\Permission;

use cmsgears\modules\core\admin\models\forms\RoleBinderForm;

use cmsgears\modules\core\admin\services\PermissionService;

use cmsgears\modules\core\common\utilities\MessageUtil;
use cmsgears\modules\core\common\utilities\AjaxUtil;

class PermissionController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'permissions' => [
	                'bindRoles'  => Permission::PERM_RBAC
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

	// PermissionController

	public function actionBindRoles() {

		$binder = new RoleBinderForm();

		if( $binder->load( Yii::$app->request->post(), "" ) ) {

			if( PermissionService::bindRoles( $binder ) ) {

				// Trigger Ajax Success
				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
        AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>