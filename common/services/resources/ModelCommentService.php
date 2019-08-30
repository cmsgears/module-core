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

use cmsgears\files\components\FileManager;

use cmsgears\core\common\services\interfaces\resources\IModelCommentService;

use cmsgears\core\common\services\traits\resources\DataTrait;

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

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$userTable = Yii::$app->factory->get( 'userService' )->getModelTable();

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
					'asc' => [ "$userTable.name" => SORT_ASC ],
					'desc' => [ "$userTable.name" => SORT_DESC ],
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
			'defaultOrder' => [
				'id' => SORT_DESC
			]
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
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

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
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'user' => "concat(creator.firstName, ' ', creator.lastName)",
				'name' => "$modelTable.name",
				'email' =>  "$modelTable.email",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'user' => "concat(creator.firstName, ' ', creator.lastName)",
			'name' => "$modelTable.name",
			'email' => "$modelTable.email",
			'content' => "$modelTable.content",
			'status' => "$modelTable.status",
			'rating' => "$modelTable.rating",
			'featured' => "$modelTable.featured"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$topLevel = isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ]	= $parentType;

		return $this->getPage( $config );
	}

	public function getCommentPageByParent( $parentId, $parentType, $config = [] ) {

        $modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = ModelComment::TYPE_COMMENT;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getReviewPageByParent( $parentId, $parentType, $config = [] ) {

       $modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = ModelComment::TYPE_REVIEW;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getPageByParentType( $parentType, $config = [] ) {

		$topLevel = isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ 'parentType' ]	= $parentType;

		return $this->getPage( $config );
	}

	public function getPageByBaseId( $baseId, $config = [] ) {

		$config[ 'conditions' ][ 'baseId' ] = $baseId;

		return $this->getPage( $config );
	}

	/**
	 * We can pass parentType as condition to utilize the classification.
	 */
	public function getPageForApproved( $config = [] ) {

		$modelTable	= $this->getModelTable();
		$topLevel	= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ "$modelTable.status" ]	= ModelComment::STATUS_APPROVED;

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

		$user = Yii::$app->user->getIdentity();

		return $modelClass::findByUser( $parentId, $parentType, $user->id );
	}

	public function isExistByUser( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		$user = Yii::$app->user->getIdentity();

		if( isset( $user ) ) {

			return $modelClass::isExistByUser( $parentId, $parentType, $user->id );
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
		$query		= $modelClass::queryByType( $parentId, $parentType, $type, $config );

		return $query->andWhere( [ 'featured' => true ] )->all();
	}

	public function getFeaturedTestimonials( $parentId, $parentType, $config = [] ) {

		$modelClass	= self::$modelClass;

		return $this->getFeaturedByType( $parentId, $parentType, $modelClass::TYPE_TESTIMONIAL, $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $comment, $config = [] ) {

		$modelClass = static::$modelClass;

		$comment->agent	= Yii::$app->request->userAgent;
		$comment->ip	= Yii::$app->request->userIP;

		// Default New
		$comment->status = $comment->status ?? $modelClass::STATUS_NEW;

		return parent::create( $comment, $config );
	}

	public function attachMedia( $model, $file, $mediaType, $parentType ) {

		switch( $mediaType ) {

			case FileManager::FILE_TYPE_IMAGE: {

				$file = $this->fileService->saveImage( $file );

				break;
			}
			case FileManager::FILE_TYPE_MIXED: {

				if( in_array( $file->extension, Yii::$app->fileManager->imageExtensions ) ) {

					$file = $this->fileService->saveImage( $file );
				}
				else {

					$file = $this->fileService->saveFile( $file );
				}

				break;
			}
			default: {

				$file = $this->fileService->saveFile( $file );

				break;
			}
		}

		// Create Model File
		if( $file->id > 0 ) {

			$this->modelFileService->createByParams( [ 'modelId' => $file->id, 'parentId' => $model->id, 'parentType' => $parentType ] );
		}

		return $file;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content',
			'rate1', 'rate2', 'rate3', 'rate4', 'rate5'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status', 'order', 'pinned', 'featured' ] );
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Various states

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		return $model;
	}

	public function approve( $model ) {

		$model->approvedAt	= DateUtil::getDateTime();

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

	// Attributes

	public function updateSpamRequest( $model, $value = true ) {

		$model->setDataMeta( CoreGlobal::META_COMMENT_SPAM_REQUEST, $value );

		$model->update();

		return $model;
	}

	public function updateDeleteRequest( $model, $value = true ) {

		$model->setDataMeta( CoreGlobal::META_COMMENT_DELETE_REQUEST, $value );

		$model->update();

		return $model;
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Files
		$this->fileService->deleteMultiple( $model->files );

		// Delete File Mappings - Shared Files
		$this->modelFileService->deleteMultiple( $model->modelFiles );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approved': {

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
					case 'blocked': {

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
