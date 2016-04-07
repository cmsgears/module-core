<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\services\entities\RoleService;

/**
 * The class SiteMemberService is base class to perform database activities for SiteMember Entity.
 */
class SiteMemberService extends \cmsgears\core\common\services\base\Service {

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

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new SiteMember(), $config );
	}

	// Create --------------

	public static function create( $user, $siteMember = null, $roleSlug = null ) {

		if( !isset( $siteMember ) ) {

			$siteMember	= new SiteMember();

			if( isset( $roleSlug ) ) {

				$role				= RoleService::findBySlug( $roleSlug );
				$siteMember->roleId	= $role->id;
			}
			else {

				$role				= RoleService::findBySlug( CoreGlobal::ROLE_USER );
				$siteMember->roleId	= $role->id;
			}
		}

		$siteMember->siteId = Yii::$app->cmgCore->siteId;
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