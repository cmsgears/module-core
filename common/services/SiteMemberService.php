<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\SiteMember;

class SiteMemberService extends Service {

	// Static Methods ----------------------------------------------

	// Read

	public static function findBySiteIdUserId( $siteId, $userId ) {

		return SiteMember::findBySiteIdUserId( $siteId, $userId );
    }
}

?>