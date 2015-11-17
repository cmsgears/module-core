<?php
namespace cmsgears\core\admin\components;

// Yii Imports
use \Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * The component sidebar forms the sidebar for admin by merging the sidebar html for the modules specified in application configuration. This sidebar is specific only for Admin Application. 
 */
class Sidebar extends Component {

	public $modules;
	
	public $plugins;

	/**
	 * Initialise the Sidebar.
	 */
    public function init() {

        parent::init();
    }

	/**
	 * @return string - the html merged from each module sidebar view.
	 */
	public function getSidebarHtml( $parent, $child ) {

		// TODO: Use caching

		$sidebarHtml	= "";
		$modules		= $this->modules;
		
		// Collect sidebar html from all the modules
		foreach ( $modules as $module ) {

			$module		= Yii::$app->getModule( $module );
			$html   	= $module->getSidebarHtml();
			
			ob_start();
			
			if( file_exists( $html ) ) {

				include( $html );
			}
			
			$sidebarHtml .= ob_get_contents();
			
			ob_get_clean();
		}

		return $sidebarHtml;
	}

	public function getConfig() {

		$config 	= [];
		$modules	= $this->modules;

		// Collect settings from all the modules
		foreach ( $modules as $module ) {

			$module		= Yii::$app->getModule( $module );

			if( isset( $module->config  ) ) {

				$config   = ArrayHelper::merge( $config, $module->config );
			}
		}
		
		$plugins	= $this->plugins;

		// Collect settings from all the plugins
		foreach ( $plugins as $plugin ) {

			if( isset( $plugin  ) ) {

				$config   = ArrayHelper::merge( $config, $plugin );
			}
		}

		return $config;
	}
}

?>