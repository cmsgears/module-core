<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
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

	// Create --------------

	public static function create( $user, $siteMember = null ) {

		// Find Current Site
		$site = Site::findByName( Yii::$app->cmgCore->getSiteName() );

		if( !isset( $siteMember ) ) {

			$siteMember			= new SiteMember();
			$role				= RoleService::findByName( CoreGlobal::ROLE_USER );
			$siteMember->roleId	= $role->id;
		}

		$siteMember->siteId = $site->id;
		$siteMember->userId	= $user->id;

		$siteMember->save();

		return $siteMember;
	}

	// Update --------------

	public static function update( $siteMember ) {

		$siteMemberToUpdate	= self::findBySiteIdUserId( $siteMember->siteId, $siteMember->userId );

		$siteMemberToUpdate->copyForUpdateFrom( $siteMember, [ 'roleId' ] );

		$siteMemberToUpdate->update();

		return $siteMemberToUpdate;
	}

	// Delete --------------

	public static function delete( $siteMember ) {

		$siteMemberToUpdate	= self::findBySiteIdUserId( $siteMember->siteId, $siteMember->userId );

		$siteMemberToUpdate->delete();

		return true;
	}
}

?>