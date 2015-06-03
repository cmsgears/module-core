<?php
namespace cmsgears\core\admin\components;

// Yii Imports
use \Yii;
use yii\base\Component;

/**
 * The component sidebar forms the sidebar for admin by merging the sidebar html for the modules specified in application configuration. This sidebar is specific only for Admin Application. 
 */
class Sidebar extends Component {

	public $modules;

	/**
	 * Initialise the Sidebar.
	 */
    public function init() {

        parent::init();
    }

	/**
	 * @return string - the html merged from each module sidebar view.
	 */
	public function getSidebarHtml() {

		// TODO: Use caching

		$sidebarHtml	= "";
		$modules		= $this->modules;

		foreach ( $modules as $module ) {

			$module		= Yii::$app->getModule( $module );
			$html   	= $module->getSidebarHtml();

			ob_start();

			include( $html );

			$sidebarHtml	.= ob_get_clean();
		}

		return $sidebarHtml;
	}
}

?>