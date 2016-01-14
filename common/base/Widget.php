<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;

abstract class Widget extends \yii\base\Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	// html options for Yii Widget
	public $options 		= [];
	
	/**
	 * Flag to check whether assets can be loaded. We can load widget assets seperately in case the bundle is not added as dependency to layout asset bundle.
	 */
	public $loadAssets		= false;

	/**
	 * The path at which view template file is located. It can have alias - ex: '@widget/my-view'. By default it's the views folder within widget directory.
	 */
	public $templateDir		= null;

	/**
	 * The template directory/file used to render widget. If it's a directory, the view can be formed using multiple files.
	 */
	public $template		= 'simple';

	// Instance Methods --------------------------------------------

	// yii\base\Widget

	/**
	 * The method returns the view path for this widget if set while calling widget. 
	 */
	public function getViewPath() {

		if( isset( $this->templateDir ) ) {

			return $this->templateDir;
		}

		return parent::getViewPath();
	}

	abstract public function renderWidget( $config = [] );
}

?>