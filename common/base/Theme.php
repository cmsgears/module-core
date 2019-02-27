<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

// Yii Imports
use Yii;
use yii\base\Theme as BaseTheme;

/**
 * Theme represents an application theme.
 *
 * @since 1.0.0
 */
class Theme extends BaseTheme {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * @var array Map of child themes. Child themes override or add additional features to parent theme.
	 */
	public $childs = [];

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
		$themeChilds = $this->childs;

		foreach( $themeChilds as $child ) {

			$child = Yii::createObject( $child );

			$child->registerAssets( $view );
		}
	}

}
