<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\FileTrait;
use cmsgears\core\common\models\traits\MetaTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Gallery Entity - The primary class.
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class Gallery extends NamedCmgEntity {

	use FileTrait;

	public $fileType	= CoreGlobal::TYPE_GALLERY;

	use MetaTrait;

	public $metaType	= CoreGlobal::TYPE_GALLERY;

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

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
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
}

?>