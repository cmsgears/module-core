<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\guidelines;

// Yii Imports
use Yii;
use yii\base\BaseObject;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

// Project Imports

/**
 * StaticTemplate can be used as template for classes having static methods without any special categorization.
 *
 * Classes using static template can be further divided into below mentioned sections:
 * <ol>
 *	<li>Yii imports</li>
 *	<li>CMG imports</li>
 *	<li>Project imports</li>
 *	<li>Class definition</li>
 *	<li>Global Variables
 *		<ol>
 *			<li>Constants</li>
 *			<li>Public</li>
 *			<li>Protected</li>
 *		</ol>
 *	</li>
 *	<li>Static methods
 *		<ol>
 *			<li>Yii parent class overridden methods</li>
 *			<li>CMG parent class overridden methods</li>
 *			<li>Current class methods</li>
 *		</ol>
 *	</li>
 *	<li>Class closure</li>
 * </ol>
 *
 * @since 1.0.0
 */
class DefaultTemplate extends BaseObject {

	// Global Variables --------------------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// <Class> -------------------------------

}
