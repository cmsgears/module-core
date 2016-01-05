<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\models\traits\FileTrait;
use cmsgears\core\common\models\traits\AttributeTrait;
use cmsgears\core\common\models\traits\CategoryTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Gallery Entity - The primary class.
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $title
 * @property string $description
 * @property short  $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Gallery extends NamedCmgEntity {

	use FileTrait;

	public $fileType		= CoreGlobal::TYPE_GALLERY;

	use AttributeTrait;

	public $attributeType	= CoreGlobal::TYPE_GALLERY;

	use CategoryTrait;

	public $categoryType	= CoreGlobal::TYPE_GALLERY;

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	/**
	 * @return string representation of flag
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active ); 
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'authorBehavior' => [
                'class' => AuthorBehavior::className()
			],
            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model ---------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'title', 'description' ], 'safe' ],
            [ [ 'name', 'type' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'slug', 'string', 'min' => 1, 'max' => 150 ],
            [ 'active', 'boolean' ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'title' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'title' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// Gallery ----------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ----------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_GALLERY;
	}

	// Gallery ----------------------------

	/**
	 * @return ActiveRecord - with site member and role.
	 */
	public static function findWithOwner() {

		return self::find()->joinWith( 'creator' );
	}

	/**
	 * @return Gallery - by slug
	 */
	public static function findBySlug( $slug ) {

		return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
	}
}

?>