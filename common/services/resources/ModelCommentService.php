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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\files\components\FileManager;

use cmsgears\core\common\services\interfaces\resources\IModelCommentService;

use cmsgears\core\common\services\base\ModelResourceService;

use cmsgears\core\common\services\traits\resources\DataTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * ModelCommentService provide service methods of model comment.
 *
 * @since 1.0.0
 */
class ModelCommentService extends ModelResourceService implements IModelCommentService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\ModelComment';

	public static $modelTable	= CoreTables::TABLE_MODEL_COMMENT;

	public static $parentType	= CoreGlobal::TYPE_COMMENT;

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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ 'id' => SORT_ASC ],
					'desc' => [ 'id' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'user' => [
					'asc' => [ "creator.firstName" => SORT_ASC, "creator.lastName" => SORT_ASC ],
					'desc' => [ "creator.firstName" => SORT_DESC, "creator.lastName" => SORT_DESC ],
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

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'user' => "concat(creator.firstName,creator.lastName)", 'name' => "$modelTable.name",
				'email' =>  "$modelTable.email", 'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'user' => "concat(creator.firstName,creator.lastName)", 'name' => "$modelTable.name",
			'email' => "$modelTable.email", 'content' => "$modelTable.content",
			'status' => "$modelTable.status", 'rating' => "$modelTable.rating", 'featured' => "$modelTable.featured"
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

        $modelTable  = self::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ] = ModelComment::TYPE_COMMENT;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getReviewPageByParent( $parentId, $parentType, $config = [] ) {

        $modelTable  = self::$modelTable;

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

		$modelTable = self::$modelTable;
		$topLevel	= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ "$modelTable.status" ]	= ModelComment::STATUS_APPROVED;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByUser( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;
		$user		= Yii::$app->user->getIdentity();

		return $modelClass::findByUser( $parentId, $parentType, $user->id );
	}

	public function isExistByUser( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;
		$user		= Yii::$app->user->getIdentity();

		if( isset( $user ) ) {

			return $modelClass::isExistByUser( $parentId, $parentType, $user->id );
		}

		return false;
	}

	public function getByParentConfig( $parentId, $config = [] ) {

		return ModelComment::queryByParentConfig( $parentId, $config )->andWhere( [ 'baseId' => null ] )->all();
	}

	public function getByParentTypeConfig( $parentType, $config = [] ) {

		return ModelComment::queryByParentTypeConfig( $parentType, $config )->andWhere( [ 'baseId' => null ] )->all();
	}

	/**
	 * It returns child comments for given base id.
	 */
	public function getByBaseId( $baseId, $config = [] ) {

		return ModelComment::queryByBaseId( $baseId, $config )->all();
	}

	/**
	 * It can be used in cases where only one comment is allowed for an email.
	 */
	public function isExistByEmail( $email ) {

		return null != self::getByEmail( $email );
	}

	public function getByEmail( $email ) {

		return ModelComment::queryByEmail( $email )->one();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $comment, $config = [] ) {

		$comment->agent	= Yii::$app->request->userAgent;
		$comment->ip	= Yii::$app->request->userIP;

		return parent::create( $comment, $config );
	}

	public function attachMedia( $model, $file, $mediaType, $parentType ) {

		switch( $mediaType ) {

			case FileManager::FILE_TYPE_IMAGE : {

				$this->fileService->saveImage( $file );

				break;
			}
			default: {

				$this->fileService->saveFile( $file );

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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content' ];

		// Allows admin to update status
		if( isset( $config[ 'admin' ] ) && $config[ 'admin' ] ) {

			$attributes[] = 'status';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Various states

	public function updateStatus( $model, $status ) {

		$model->status	= $status;

		$model->update();
	}

	public function approve( $model ) {

		$model->approvedAt	= DateUtil::getDateTime();

		$this->updateStatus( $model, ModelComment::STATUS_APPROVED );
	}

	public function block( $model ) {

		$this->updateStatus( $model, ModelComment::STATUS_BLOCKED );
	}

	public function markSpam( $model ) {

		$this->updateStatus( $model, ModelComment::STATUS_SPAM );
	}

	public function markTrash( $model ) {

		$this->updateStatus( $model, ModelComment::STATUS_TRASH );
	}

	// Attributes

	public function updateSpamRequest( $model ) {

		$model->setDataMeta( CoreGlobal::META_COMMENT_SPAM_REQUEST, true );

		$model->update();

		return $model;
	}

	public function updateDeleteRequest( $model ) {

		$model->setDataMeta( CoreGlobal::META_COMMENT_DELETE_REQUEST, true );

		$model->update();

		return $model;
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'block': {

						$this->block( $model );

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
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete files
		$this->modelFileService->deleteMultiple( $model->modelFiles );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

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
