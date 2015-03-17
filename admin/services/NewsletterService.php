<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Newsletter;

use cmsgears\core\common\utilities\DateUtil;

class NewsletterService extends \cmsgears\core\common\services\NewsletterService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination() {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'cdate' => [
	                'asc' => [ 'createdOn' => SORT_ASC ],
	                'desc' => ['createdOn' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedOn' => SORT_ASC ],
	                'desc' => ['modifiedOn' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ],
	            'ldate' => [
	                'asc' => [ 'lastSentOn' => SORT_ASC ],
	                'desc' => ['lastSentOn' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Newsletter(), [ 'sort' => $sort, 'search-col' => 'name' ] );
	}

	// Create -----------

	public static function create( $newsletter ) {
		
		// Set Attributes
		$date					= DateUtil::getMysqlDate();
		$user					= Yii::$app->user->getIdentity();
		$newsletter->createdBy	= $user->id;
		$newsletter->createdOn	= $date;

		// Create Newsletter
		$newsletter->save();

		// Return Newsletter
		return $newsletter;
	}

	// Update -----------

	public static function update( $newsletter ) {
		
		// Find existing Newsletter
		$nlToUpdate	= self::findById( $newsletter->id );
		
		// Copy and set Attributes	
		$date					= DateUtil::getMysqlDate();
		$user					= Yii::$app->user->getIdentity();
		$nlToUpdate->modifiedBy	= $user->id;
		$nlToUpdate->modifiedOn	= $date;

		$nlToUpdate->copyForUpdateFrom( $newsletter, [ 'name', 'description', 'content' ] );
		
		// Update Newsletter
		$nlToUpdate->update();
		
		// Return updated Newsletter
		return $nlToUpdate;
	}

	// Delete -----------

	public static function delete( $newsletter ) {

		// Find existing Newsletter
		$nlToDelete	= self::findById( $newsletter->id );

		// Delete Newsletter
		$nlToDelete->delete();

		return true;
	}
}

?>