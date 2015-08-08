<?php
namespace cmsgears\core\admin\components;

// Yii Imports
use \Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * The component dashboard forms the dashboard for admin by merging the dashboard html for the modules specified in application configuration. This dashboard is specific only for Admin Application. 
 */
class Dashboard extends Component {

	public $modules;

	/**
	 * Initialise the Dashboard.
	 */
    public function init() {

        parent::init();
    }

	/**
	 * @return string - the html merged from each module sidebar view.
	 */
	public function getDashboardHtml() {

		// TODO: Use caching

		$sidebarHtml	= "";
		$modules		= $this->modules;
		
		// Collect sidebar html from all the modules
		foreach ( $modules as $module ) {

			$module		= Yii::$app->getModule( $module );
			$html   	= $module->getDashboardHtml();

			ob_start();
			
			if( file_exists( $html ) ) {

				include( $html );
			}
			
			$sidebarHtml = ob_get_contents();
			
			ob_get_clean();
		}

		return $sidebarHtml;
	}
}

?>