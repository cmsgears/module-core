<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\mappers;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IFollower;
use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\FollowerTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * The model follower records user following a model based on interest.
 *
 * @property int $id
 * @property int $modelId
 * @property int $parentId
 * @property int $parentType
 * @property string $type
 * @property int $order
 * @property boolean $active
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property int $createdAt
 * @property int $modifiedAt
 * @property string $data
 */
class ModelFollower extends \cmsgears\core\common\models\base\ModelMapper implements IData, IFeatured, IFollower {

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
	use FeaturedTrait;
	use FollowerTrait;

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

		$rules = parent::rules();

		$rules[] = [ [ 'pinned', 'featured', 'popular' ], 'boolean' ];
		$rules[] = [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ];

		return $rules;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelFollower -------------------------

	/**
	 * Return the user associated with the mapping.
	 *
	 * @return User
	 */
	public function getModel() {

		return $this->hasOne( User::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::getTables( CoreTables::TABLE_MODEL_FOLLOWER );
	}

	// CMG parent classes --------------------

	// ModelFollower -------------------------

	// Read - Query -----------

	/**
	 * Return query to find the mapping by type and follower id.
	 *
	 * @param integer $type
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \yii\db\ActiveQuery to query by type and follower id.
	 */
	public static function queryByTypeParentTypeModelId( $type, $parentType, $modelId ) {

		return self::find()->where( 'type=:type AND parentType=:ptype AND modelId=:mid', [
			':type' => $type, ':parentType' => $parentType, ':mid' => $modelId
		]);
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
