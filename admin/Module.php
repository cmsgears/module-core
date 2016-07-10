<?php
namespace cmsgears\core\admin;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Module extends \cmsgears\core\common\base\Module {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

    public $controllerNamespace = 'cmsgears\core\admin\controllers';

	public $config 				= [ CoreGlobal::CONFIG_CORE, CoreGlobal::CONFIG_MAIL, CoreGlobal::CONFIG_ADMIN, CoreGlobal::CONFIG_FRONTEND ];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-core/admin/views' );
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

	public function getSidebarHtml() {

		$path	= Yii::getAlias( '@cmsgears' ) . '/module-core/admin/views/sidebar.php';

		return $path;
	}
}
