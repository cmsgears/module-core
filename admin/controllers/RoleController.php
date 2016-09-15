<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class RoleController extends \cmsgears\core\admin\controllers\base\RoleController {

    // Variables ---------------------------------------------------

    // Globals ----------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->sidebar 			= [ 'parent' => 'sidebar-identity', 'child' => 'role' ];

        $this->returnUrl		= Url::previous( 'roles' );
        $this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/role/all' ], true );
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    // yii\base\Controller ----

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // RoleController ------------------------

    public function actionAll() {

        // Remember return url for crud
        Url::remember( [ 'role/all' ], 'roles' );

        return parent::actionAll();
    }
}
