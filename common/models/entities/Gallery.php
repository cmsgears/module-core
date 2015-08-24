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

use cmsgears\core\common\models\traits\FileTrait;
use cmsgears\core\common\models\traits\MetaTrait;
use cmsgears\core\common\models\traits\CategoryTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Gallery Entity - The primary class.
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 */
class Gallery extends NamedCmgEntity {

	use FileTrait;

	public $fileType		= CoreGlobal::TYPE_GALLERY;

	use MetaTrait;

	public $metaType		= CoreGlobal::TYPE_GALLERY;

	use CategoryTrait;

	public $categoryType	= CoreGlobal::TYPE_GALLERY;

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	/**
	 * @return boolean - whether given user is owner
	 */
	public function checkOwner( $user ) {
		
		return $this->createdBy	= $user->id;		
	}
	
	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

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

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'title' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'slug', 'description', 'title' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

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
			'title' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION )
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