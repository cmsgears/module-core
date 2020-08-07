<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\base;

/**
 * The IMultiSite interface provide methods specific to models supporting multi-site features.
 *
 * @since 1.0.0
 */
interface IMultiSite {

	public function getSite();

}
