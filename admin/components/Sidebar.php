<?php
namespace cmsgears\core\admin\components;

// Yii Imports
use \Yii;
use yii\base\Component;

class Sidebar extends Component {

	public $modules;

	/**
	 * Initialise the Sidebar.
	 */
    public function init() {

        parent::init();
    }

	public function getSidebarHtml() {

		$sidebarHtml	= "";
		$modules		= $this->modules;

		foreach ( $modules as $module ) {

			$module		= Yii::$app->getModule( $module );
			$module   	= $module->getSidebarHtml();

			ob_start();
			
			include( $module );
	
			$sidebarHtml	.= ob_get_clean();
		}

		return $sidebarHtml;
	}
}

?>