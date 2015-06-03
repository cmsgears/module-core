<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\SiteMember;

/**
 * The class SiteMemberService is base class to perform database activities for SiteMember Entity.
 */
class SiteMemberService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $siteId
	 * @param integer $userId
	 * @return SiteMember - for the given site and user
	 */
	public static function findBySiteIdUserId( $siteId, $userId ) {

		return SiteMember::findBySiteIdUserId( $siteId, $userId );
    }
}

?>