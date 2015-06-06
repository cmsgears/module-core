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

	public static function getPagination( $conditions = [], $query = null ) {

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
	                'asc' => [ 'roleId' => SORT_ASC ],
	                'desc' => ['roleId' => SORT_DESC ],
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
		
		$status	= Yii::$app->request->getQueryParam( "status" );

		if( isset( $status ) ) {

			$conditions['status'] = $status;
		}

		$conditions["site.name"] 	= Yii::$app->cmgCore->getSiteName();

		if( isset( $query ) ) {

			return self::getDataProvider( new User(), [ 'conditions' => $conditions, 'query' => $query, 'sort' => $sort, 'search-col' => 'firstName' ] );
		}
		else {

			return self::getDataProvider( new User(), [ 'conditions' => $conditions, 'sort' => $sort, 'search-col' => 'firstName' ] );
		}
	}

	public static function getPaginationByAdmins() {

		$permission					= CoreTables::TABLE_PERMISSION;

		return self::getPagination( [ "$permission.name" => CoreGlobal::PERM_ADMIN ], User::findWithSiteMemberPermission() );
	}

	public static function getPaginationByUsers() {

		$permission					= CoreTables::TABLE_PERMISSION;

		return self::getPagination( [ "$permission.name" => CoreGlobal::PERM_USER ], User::findWithSiteMemberPermission() );
	}

	public static function getPaginationByNewsletter() {

		return self::getPagination( [ 'newsletter' => 1 ], User::findWithSiteMember() );
	}
}

?>