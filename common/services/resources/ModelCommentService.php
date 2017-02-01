<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\common\services\interfaces\resources\IModelCommentService;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends \cmsgears\core\common\services\base\EntityService implements IModelCommentService {

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

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelCommentService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name'
				],
				'email' => [
					'asc' => [ 'email' => SORT_ASC ],
					'desc' => ['email' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'email'
				],
				'rating' => [
					'asc' => [ 'rating' => SORT_ASC ],
					'desc' => ['rating' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'rating'
				],
				'cdate' => [
					'asc' => [ 'createdAt' => SORT_ASC ],
					'desc' => ['createdAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'cdate'
				],
				'udate' => [
					'asc' => [ 'updatedAt' => SORT_ASC ],
					'desc' => ['updatedAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'udate'
				],
				'adate' => [
					'asc' => [ 'approvedAt' => SORT_ASC ],
					'desc' => ['approvedAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'adate'
				]
			],
			'defaultOrder' => [
				'adate' => SORT_DESC
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$config[ 'conditions' ][ 'baseId' ]		= null;
		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ]	= $parentType;

		return $this->getPage( $config );
	}

	public function getCommentPageByParent( $parentId, $parentType, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = ModelComment::TYPE_COMMENT;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getReviewPageByParent( $parentId, $parentType, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = ModelComment::TYPE_REVIEW;

		return $this->getPageByParent( $parentId, $parentType, $config );
	}

	public function getPageByParentType( $parentType, $config = [] ) {

		$config[ 'conditions' ][ 'baseId' ]		= null;
		$config[ 'conditions' ][ 'parentType' ]	= $parentType;

		return $this->getPage( $config );
	}

	public function getPageByBaseId( $baseId, $config = [] ) {

		$config[ 'conditions' ][ 'baseId' ]		= null;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByParent( $parentId, $parentType, $config = [] ) {

		return ModelComment::queryByParentConfig( $parentId, $config )->andWhere( [ 'baseId' => null ] )->all();
	}

	public function getByParentType( $parentType, $config = [] ) {

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

	public function markDelete( $model ) {

		$this->updateStatus( $model, ModelComment::STATUS_DELETED );
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

	// Delete -------------

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
