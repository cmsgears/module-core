<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

class SettingsController extends \cmsgears\core\admin\controllers\base\Controller {

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= AdminGlobalCore::LAYOUT_PRIVATEN;

		$this->sidebar 	= [ 'parent' => 'sidebar-settings', 'child' => 'settings' ];
	}

	// yii\base\Component ----------------

	public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ]
                ]
            ]
        ];
    }

	// SettingsController ----------------

    public function actionIndex() {

	    return $this->render( 'index' );
    }
}

?>