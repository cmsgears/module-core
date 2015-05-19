<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\SiteMember;

class SiteMemberService extends \cmsgears\core\common\services\SiteMemberService {

	// Static Methods ----------------------------------------------

	// Create

	public static function create( $user, $siteMember ) {

		// Find Current Site
		$site = Site::findByName( Yii::$app->cmgCore->getSiteName() );

		$siteMember->siteId = $site->id;
		$siteMember->userId	= $user->id;

		$siteMember->save();

		return $siteMember;
	}

	// Update

	public static function update( $siteMember ) {

		$siteMemberToUpdate	= self::findBySiteIdUserId( $siteMember->siteId, $siteMember->userId );

		$siteMemberToUpdate->copyForUpdateFrom( $siteMember, [ 'roleId' ] );

		$siteMemberToUpdate->update();

		return $siteMemberToUpdate;
	}
}

?>