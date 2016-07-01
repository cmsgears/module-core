<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * @var array $childs is a map of child themes. We can use child theme to override parent theme assets using it's assets bundles.
 */
class Theme extends \yii\base\Theme {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $childs		= [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

		// The path for images directly accessed using the img tag
		Yii::setAlias( '@images', '@web/images' );
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Theme ---------------------------------

	public function registerChildAssets( $view ) {

		// register child theme assets from config
		$themeChilds	= $this->childs;

		foreach ( $themeChilds as $child ) {

			$child = Yii::createObject( $child );

			$child->registerAssets( $view );
		}
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Theme ---------------------------------

}

?>