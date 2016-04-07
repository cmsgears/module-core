<?php
namespace cmsgears\core\admin\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

class UserService extends \cmsgears\core\common\services\entities\UserService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $config = [] ) {

		$siteTable 			= CoreTables::TABLE_SITE;
		$siteMemberTable 	= CoreTables::TABLE_SITE_MEMBER;

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'firstName' => SORT_ASC, 'lastName' => SORT_ASC ],
	                'desc' => [ 'firstName' => SORT_DESC, 'lastName' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'username' => [
	                'asc' => [ 'username' => SORT_ASC ],
	                'desc' => ['username' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'username',
	            ],
	            'role' => [
	                'asc' => [ "$siteMemberTable.roleId" => SORT_ASC ],
	                'desc' => [ "$siteMemberTable.roleId" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'role',
	            ],
	            'status' => [
	                'asc' => [ 'status' => SORT_ASC ],
	                'desc' => ['status' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'status',
	            ],
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email',
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'email';
		}

		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ] = [];
		}

		$siteTable										= CoreTables::TABLE_SITE;
		$config[ 'conditions' ][ "$siteTable.slug" ] 	= Yii::$app->cmgCore->getSiteSlug();

		return self::getDataProvider( new User(), $config );
	}

	public static function getPaginationByRoleType( $roleType ) {

		$roleTable = CoreTables::TABLE_ROLE;

		return self::getPagination( [ 'conditions' => [ "$roleTable.type" => $roleType ], 'query' => User::findWithSiteMember() ] );
	}

	public static function getPaginationByRoleSlug( $roleSlug ) {

		$roleTable = CoreTables::TABLE_ROLE;

		return self::getPagination( [ 'conditions' => [ "$roleTable.slug" => $roleSlug ], 'query' => User::findWithSiteMember() ] );
	}

	public static function getPaginationByPermissionSlug( $permissionSlug ) {

		$permissionTable = CoreTables::TABLE_PERMISSION;

		return self::getPagination( [ 'conditions' => [ "$permissionTable.slug" => $permissionSlug ], 'query' => User::findWithSiteMemberPermission() ] );
	}

	public static function getPaginationByAdmins() {

		return self::getPaginationByPermissionName( CoreGlobal::PERM_ADMIN );
	}

	public static function getPaginationByUsers() {

		return self::getPaginationByPermissionName( CoreGlobal::PERM_USER );
	}
}

?>