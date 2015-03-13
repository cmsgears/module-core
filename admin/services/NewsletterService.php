<?php
namespace cmsgears\modules\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\core\common\models\entities\Newsletter;

use cmsgears\modules\core\common\utilities\DateUtil;

class NewsletterService extends \cmsgears\modules\core\common\services\NewsletterService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination() {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'newsletter_name' => SORT_ASC ],
	                'desc' => ['newsletter_name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'cdate' => [
	                'asc' => [ 'newsletter_created_on' => SORT_ASC ],
	                'desc' => ['newsletter_created_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'newsletter_updated_on' => SORT_ASC ],
	                'desc' => ['newsletter_updated_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ],
	            'ldate' => [
	                'asc' => [ 'newsletter_last_sent_on' => SORT_ASC ],
	                'desc' => ['newsletter_last_sent_on' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Newsletter(), [ 'sort' => $sort, 'search-col' => 'newsletter_name' ] );
	}

	// Create -----------

	public static function create( $newsletter ) {
		
		$date	= DateUtil::getMysqlDate();
		
		$newsletter->setCreatedOn( $date );
		$newsletter->setUpdatedOn( $date );

		$newsletter->save();

		return true;
	}

	// Update -----------

	public static function update( $newsletter ) {
		
		$date				= DateUtil::getMysqlDate();
		$newsletterToUpdate	= self::findById( $newsletter->getId() );

		$newsletterToUpdate->setName( $newsletter->getName() );
		$newsletterToUpdate->setDesc( $newsletter->getDesc() );
		$newsletterToUpdate->setContent( $newsletter->getContent() );
		$newsletterToUpdate->setUpdatedOn( $date );

		$newsletterToUpdate->update();

		return true;
	}

	// Delete -----------

	public static function delete( $newsletter ) {

		$newsletterId		= $newsletter->getId();
		$existingNewsletter	= self::findById( $newsletterId );

		// Delete Newsletter
		$existingNewsletter->delete();

		return true;
	}
}

?>