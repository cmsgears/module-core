<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\User;

class UserService extends \cmsgears\core\common\services\UserService {

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
		$config[ 'conditions' ][ "$siteTable.name" ] 	= Yii::$app->cmgCore->getSiteName();

		return self::getDataProvider( new User(), $config );
	}

	public static function getPaginationByAdmins() {

		$permissionTable = CoreTables::TABLE_PERMISSION;

		return self::getPagination( [ 'conditions' => [ "$permissionTable.name" => CoreGlobal::PERM_ADMIN ], 'query' => User::findWithSiteMemberPermission() ] );
	}

	public static function getPaginationByUsers() {

		$permissionTable = CoreTables::TABLE_PERMISSION;

		return self::getPagination( [ 'conditions' => [ "$permissionTable.name" => CoreGlobal::PERM_USER ], 'query' => User::findWithSiteMemberPermission() ] );
	}
}

?>