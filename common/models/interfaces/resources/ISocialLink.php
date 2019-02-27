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
 * The ISocialLink declare the methods specific to managing social links.
 *
 * @since 1.0.0
 */
interface ISocialLink {

	/**
	 * Generate and return the map having sns key and [[\cmsgears\core\common\models\forms\SocialLink]] as value.
	 *
	 * @return array
	 */
	public function getSocialLinks();

	/**
	 * Update the social links and store them in [[$data]] attribute.
	 *
	 * @param SocialLink[] $links
	 * @return void
	 */
	public function updateSocialLinks( $links );

	/**
	 * Update the social link and store it in [[$data]] attribute.
	 *
	 * @param SocialLink $link
	 * @return void
	 */
	public function updateSocialLink( $link );

	/**
	 * Delete social link and update [[$data]] attribute.
	 *
	 * @param SocialLink $link
	 * @return void
	 */
	public function deleteSocialLink( $link );
}
