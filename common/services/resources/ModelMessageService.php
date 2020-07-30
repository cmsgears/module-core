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

use cmsgears\core\common\models\resources\ModelMessage;

use cmsgears\core\common\services\interfaces\resources\IModelMessageService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\mappers\FileTrait;

/**
 * ModelMessageService provide service methods of model comment.
 *
 * @since 1.0.0
 */
class ModelMessageService extends \cmsgears\core\common\services\base\ModelResourceService implements IModelMessageService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\ModelMessage';

	public static $parentType = CoreGlobal::TYPE_MESSAGE;

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
	use GridCacheTrait;
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

	// ModelMessageService -------------------

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
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'title' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'consumed' => [
					'asc' => [ "$modelTable.consumed" => SORT_ASC ],
					'desc' => [ "$modelTable.consumed" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Consumed'
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
		$cons	= Yii::$app->request->getQueryParam( 'consumed' );
		$trash	= Yii::$app->request->getQueryParam( 'trash' );

		// Filter - Consumed
		if( isset( $cons ) ) {

			switch( $cons ) {

				case 'new': {

					$config[ 'conditions' ][ "$modelTable.consumed" ] = false;

					break;
				}
				case 'read': {

					$config[ 'conditions' ][ "$modelTable.consumed" ] = true;

					break;
				}
			}
		}

		// Filter - Trash
		if( isset( $trash ) ) {

			switch( $trash ) {

				case 'trash': {

					$config[ 'conditions' ][ "$modelTable.trash" ] = true;

					break;
				}
				case 'active': {

					$config[ 'conditions' ][ "$modelTable.trash" ] = false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'user' => "concat(creator.firstName, ' ', creator.lastName)",
			'title' => "$modelTable.title",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'user' => "concat(creator.firstName, ' ', creator.lastName)",
			'title' => "$modelTable.title",
			'content' => "$modelTable.content",
			'consumed' => "$modelTable.consumed",
			'trash' => "$modelTable.trash"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();
		$topLevel	= isset( $config[ 'topLevel' ] ) ? $config[ 'topLevel' ] : true;

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : ModelMessage::TYPE_MESSAGE;

		if( $topLevel ) {

			$config[ 'conditions' ][ 'baseId' ] = null;
		}

		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ]	= $parentType;
		$config[ 'conditions' ][ "$modelTable.type" ]		= $type;

		return $this->getPage( $config );
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

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass = static::$modelClass;

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		//$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'title', 'content'
		];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// States -----

	public function markNew( $model ) {

		$model->consumed = false;

		return parent::update( $model, [
			'attributes' => [ 'consumed' ]
		]);
	}

	public function markConsumed( $model ) {

		$model->consumed = true;

		return parent::update( $model, [
			'attributes' => [ 'consumed' ]
		]);
	}

	public function markTrash( $model ) {

		$model->trash = true;

		return parent::update( $model, [
			'attributes' => [ 'trash' ]
		]);
	}

	public function unTrash( $model ) {

		$model->trash = false;

		return parent::update( $model, [
			'attributes' => [ 'trash' ]
		]);
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

			case 'consumed': {

				switch( $action ) {

					case 'new': {

						$this->markNew( $model );

						break;
					}
					case 'read': {

						$this->markConsumed( $model );

						break;
					}
				}

				break;
			}
			case 'trash': {

				switch( $action ) {

					case 'trash': {

						$this->markTrash( $model );

						break;
					}
					case 'untrash': {

						$this->unTrash( $model );

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

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelMessageService -------------------

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
