<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

/**
 * ModelAnalytics stores the analytics data of model.
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property integer $views
 * @property integer $referrals
 * @property integer $comments
 * @property integer $reviews
 * @property float $ratings
 * @property integer $likes
 * @property integer $wish
 * @property integer $followers
 * @property integer $rank
 * @property float $weight
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class ModelAnalytics extends \cmsgears\core\common\models\base\ModelResource implements IData, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'parentId', 'parentType' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ [ 'views', 'referrals', 'comments', 'reviews', 'likes', 'wish', 'followers', 'rank' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'ratings', 'weight' ], 'number', 'min' => 0 ],
			[ 'gridCacheValid', 'boolean' ],
			[ 'parentId', 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'gridCachedAt', 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'views' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIEW_COUNT ),
			'referrals' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REFERRAL_COUNT ),
			'comments' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COMMENTS ),
			'reviews' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REVIEWS ),
			'ratings' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RATINGS ),
			'likes' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LIKE_COUNT ),
			'wish' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WISH_COUNT ),
			'followers' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FOLLOWERS ),
			'rank' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RANK ),
			'weight' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEIGHT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_SITE;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelAnalytics ------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_ANALYTICS );
	}

	// CMG parent classes --------------------

	// ModelAnalytics ------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
