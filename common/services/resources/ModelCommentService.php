<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\common\services\interfaces\resources\IModelCommentService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\mappers\FileTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * ModelCommentService provide service methods of model comment.
 *
 * @since 1.0.0
 */
class ModelCommentService extends \cmsgears\core\common\services\base\ModelResourceService implements IModelCommentService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\ModelComment';

	public static $parentType = CoreGlobal::TYPE_COMMENT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use FileTrait;
	use MultisiteTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->fileService		= Yii::$app->factory->get( 'fileService' );
		$this->modelFileService	= Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelCommentService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'user' => [
					'asc' => [ "creator.name" => SORT_ASC ],
					'desc' => [ "creator.name" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
	            ],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'email' => [
					'asc' => [ "$modelTable.email" => SORT_ASC ],
					'desc' => [ "$modelTable.email" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Email'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'rating' => [
					'asc' => [ "$modelTable.rating" => SORT_ASC ],
					'desc' => [ "$modelTable.rating" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Rating'
				],
				'pinned' => [
					'asc' => [ "$modelTable.pinned" => SORT_ASC ],
					'desc' => [ "$modelTable.pinned" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pinned'
				],
				'featured' => [
					'asc' => [ "$modelTable.featured" => SORT_ASC ],
					'desc' => [ "$modelTable.featured" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Featured'
				],
				'popular' => [
					'asc' => [ "$modelTable.popular" => SORT_ASC ],
					'desc' => [ "$modelTable.popular" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Popular'
				],
				'anonymous' => [
					'asc' => [ "$modelTable.anonymous" => SORT_ASC ],
					'desc' => [ "$modelTable.anonymous" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Anonymous'
				],
	            'cdate' => [
	                'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ],
	            'adate' => [
	                'asc' => [ "$modelTable.approvedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.approvedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Approved At'
	            ]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Status
		if( isset( $status ) && empty( $config[ 'conditions' ][ "$modelTable.status" ] ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
				case 'popular': {

					$config[ 'conditions' ][ "$modelTable.popular" ] = true;

					break;
				}
				case 'anonymous': {

					$config[ 'conditions' ][ "$modelTable.anonymous" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'user' => "concat(creator.firstName, ' ', creator.lastName)",
			'name' => "$modelTable.name",
			'email' =>  "$modelTable.email",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'user' => "concat(creator.firstName, ' ', creator.lastName)",
			'name' => "$modelTable.name",
			'email' => "$modelTable.email",
			'content' => "$modelTable.content",
			'status' => "$modelTable.status",
			'rating' => "$modelTable.rating",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured",
			'popular' => "$modelTable.popular",
			'anonymous' => "$modelTable.anonymous"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	public function getPageByParentType( $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();
		$topLevel	= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelComment::TYPE_COMMENT;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ "$modelTable.parentType" ] = $parentType;

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		return $this->getPage( $config );
	}

	public function getReviewPageByParentType( $parentType, $config = [] ) {

		$config[ 'type' ] = ModelComment::TYPE_REVIEW;

		return $this->getPageByParent( $parentType, $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();
		$topLevel	= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelComment::TYPE_COMMENT;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ]	= $parentType;
		$config[ 'conditions' ][ "$modelTable.type" ]		= $type;

		return $this->getPage( $config );
	}

	public function getReviewPageByParent( $parentId, $parentType, $config = [] ) {

		$config[ 'type' ] = ModelComment::TYPE_REVIEW;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getPageForApproved( $parentId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.status" ]	= ModelComment::STATUS_APPROVED;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getReviewPageForApproved( $parentId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.status" ]	= ModelComment::STATUS_APPROVED;

		$config[ 'type' ] = ModelComment::TYPE_REVIEW;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getPageByBaseId( $baseId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.baseId" ] = $baseId;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * It returns immediate child comments for given base id.
	 */
	public function getByBaseId( $baseId, $config = [] ) {

		$modelClass	= self::$modelClass;

		return $modelClass::queryByBaseId( $baseId, $config )->all();
	}

	public function getByUser( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		$user = Yii::$app->core->getUser();

		return $modelClass::findByUserId( $parentId, $parentType, $user->id );
	}

	public function isExistByUser( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		$user = Yii::$app->core->getUser();

		if( isset( $user ) ) {

			return $modelClass::isExistByUserId( $parentId, $parentType, $user->id );
		}

		return false;
	}

	/**
	 * It can be used in cases where only one comment is allowed for an email.
	 */
	public function isExistByEmail( $email ) {

		return null != self::getByEmail( $email );
	}

	public function getByEmail( $email ) {

		$modelClass	= self::$modelClass;

		return $modelClass::queryByEmail( $email )->one();
	}

	public function getFeaturedByType( $parentId, $parentType, $type, $config = [] ) {

		$modelClass	= self::$modelClass;

		$config[ 'type' ] = $type;

		$query = $modelClass::queryL0Approved( $parentId, $parentType, $config );

		return $query->andWhere( [ 'featured' => true ] )->all();
	}

	public function getFeaturedReviews( $parentId, $parentType, $config = [] ) {

		return $this->getFeaturedByType( $parentId, $parentType, ModelComment::TYPE_REVIEW, $config );
	}

	public function getFeaturedTestimonials( $parentId, $parentType, $config = [] ) {

		return $this->getFeaturedByType( $parentId, $parentType, ModelComment::TYPE_TESTIMONIAL, $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;

		// Default New
		$model->status = $model->status ?? ModelComment::STATUS_NEW;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'avatarId', 'bannerId', 'videoId', 'name', 'email',
			'avatarUrl', 'websiteUrl', 'rating', 'content',
			'rate1', 'rate2', 'rate3', 'rate4', 'rate5',
			'field1', 'field2', 'field3', 'field4', 'field5'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured', 'popular', 'anonymous'
			]);
		}

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// States -----

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		return $model;
	}

	public function approve( $model ) {

		$model->approvedAt = DateUtil::getDateTime();

		return $this->updateStatus( $model, ModelComment::STATUS_APPROVED );
	}

	public function block( $model ) {

		return $this->updateStatus( $model, ModelComment::STATUS_BLOCKED );
	}

	public function markSpam( $model ) {

		return $this->updateStatus( $model, ModelComment::STATUS_SPAM );
	}

	public function markTrash( $model ) {

		return $this->updateStatus( $model, ModelComment::STATUS_TRASH );
	}

	// Requests ---

	public function spamRequest( $model, $parent, $config = [] ) {

		$parentType		= $config[ 'parentType' ];
		$notify			= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$commentType	= isset( $config[ 'commentType' ] ) ? $config[ 'commentType' ] : ModelComment::TYPE_COMMENT;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : null;

		if( $notify ) {

			$this->notifyAdmin( $model, [
				'template' => CoreGlobal::TPL_COMMENT_REQUEST_SPAM,
				'adminLink' => $adminLink,
				'data' => [ 'parent' => $parent, 'parentType' => $parentType, 'commentType' => $commentType ]
			]);
		}
	}

	public function approveRequest( $model, $parent, $config = [] ) {

		$parentType		= $config[ 'parentType' ];
		$notify			= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$commentType	= isset( $config[ 'commentType' ] ) ? $config[ 'commentType' ] : ModelComment::TYPE_COMMENT;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : null;

		if( $notify ) {

			$this->notifyAdmin( $model, [
				'template' => CoreGlobal::TPL_COMMENT_REQUEST_APPROVE,
				'adminLink' => $adminLink,
				'data' => [ 'parent' => $parent, 'parentType' => $parentType, 'commentType' => $commentType ]
			]);
		}
	}

	public function deleteRequest( $model, $parent, $config = [] ) {

		$parentType		= $config[ 'parentType' ];
		$notify			= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$commentType	= isset( $config[ 'commentType' ] ) ? $config[ 'commentType' ] : ModelComment::TYPE_COMMENT;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : null;

		if( $notify ) {

			$this->notifyAdmin( $model, [
				'template' => CoreGlobal::TPL_COMMENT_REQUEST_DELETE,
				'adminLink' => $adminLink,
				'data' => [ 'parent' => $parent, 'parentType' => $parentType, 'commentType' => $commentType ]
			]);
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Files
		$this->fileService->deleteMultiple( $model->files );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'spam': {

						$this->markSpam( $model );

						break;
					}
					case 'trash': {

						$this->markTrash( $model );

						break;
					}
					case 'block': {

						$this->block( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'pinned': {

						$model->pinned = true;

						$model->update();

						break;
					}
					case 'featured': {

						$model->featured = true;

						$model->update();

						break;
					}
					case 'popular': {

						$model->popular = true;

						$model->update();

						break;
					}
					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelCommentService -------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
