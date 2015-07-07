<?php
namespace cmsgears\core\widgets;

use \Yii;
use yii\base\Widget;

class BaseWidget extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	// html options for Yii Widget
	public $options 		= [];

	/**
	 * The path at which view file is located. It can have alias - ex: '@widget/my-view'. By default it's the views folder within widget directory.
	 */
	public $viewsDirectory	= null;

	/**
	 * The view directory/file used to render widget. If it's a directory, the view can be formed using multiple file.
	 */
	public $viewFile		= 'simple';

	// Instance Methods --------------------------------------------

	// yii\base\Widget

	/**
	 * The method returns the view path for this widget if set while calling widget. 
	 */
	public function getViewPath() {

		if( isset( $this->viewsDirectory ) ) {

			return $this->viewsDirectory;
		}

		return parent::getViewPath();
	}
}

?>