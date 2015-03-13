<?php
namespace cmsgears\modules\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\frontend\config\WebGlobalCore;

use cmsgears\modules\core\frontend\services\UserService;

use cmsgears\modules\core\common\utilities\MessageUtil;

class UserController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'index' ],
                'rules' => [
                    [
                        'actions' => [ 'index' ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

	// UserController

    public function actionIndex() {

        return $this->render( WebGlobalCore::PAGE_INDEX );
    }
}

?>