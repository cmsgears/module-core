<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\ResetPassword;

use cmsgears\core\frontend\services\UserService;

use cmsgears\core\common\utilities\AjaxUtil;

class UserController extends \cmsgears\core\common\controllers\apix\UserController {

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
                'actions' => [
	                'avatar' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'account' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'settings' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'avatar' => [ 'post' ],
                    'account' => [ 'post' ],
                    'settings' => [ 'post' ]
                ]
            ]
        ];
    }

	// UserController
}

?>