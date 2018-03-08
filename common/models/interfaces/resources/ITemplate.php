<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\resources;

/**
 * The ITemplate declare the methods specific to model template.
 *
 * @since 1.0.0
 */
interface ITemplate {

	/**
	 * Return the corresponding template.
	 *
	 * @return \cmsgears\core\common\models\entities\Template
	 */
	public function getTemplate();

	/**
	 * Return the corresponding template name.
	 *
	 * @return string
	 */
	public function getTemplateName();
}
