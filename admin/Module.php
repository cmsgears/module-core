<?php
namespace cmsgears\core\admin;

// Yii Imports
use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\core\admin\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-core/admin/views' );
    }

	public function getSidebarHtml() {

		$path	= Yii::getAlias( "@cmsgears" ) . "/module-core/admin/views/sidebar.php";

		return $path;
	}
}

?>