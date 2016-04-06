<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\ResetPassword;

use cmsgears\core\frontend\services\entities\UserService;

use cmsgears\core\common\utilities\AjaxUtil;

class UserController extends \cmsgears\core\common\controllers\apix\UserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'profile' ]			= [ 'permission' => CoreGlobal::PERM_USER ];
		$behaviours[ 'rbac' ][ 'actions' ][ 'updateAddress' ]	= [ 'permission' => CoreGlobal::PERM_USER ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'profile' ]		= [ 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'updateAddress' ]	= [ 'post' ];

		return $behaviours;
    }
}

?>