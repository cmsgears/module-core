<?php
namespace cmsgears\core\frontend;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Module extends \cmsgears\core\common\base\Module {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

    public $controllerNamespace = 'cmsgears\core\frontend\controllers';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-core/frontend/views' );
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------
}
