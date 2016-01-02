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

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Template Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $type
 * @property string $layout
 * @property string $viewPath
 * @property string $adminView
 * @property string $frontendView
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $icon
 * @property string $content
 */
class Template extends CmgEntity {

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

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

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules		
        $rules = [
            [ [ 'name', 'type' ], 'required' ],
            [ [ 'id', 'slug', 'description', 'layout', 'viewPath', 'adminView', 'frontendView', 'content', 'icon' ], 'safe' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'layout', 'viewPath', 'adminView', 'frontendView' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'layout' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LAYOUT ),
			'viewPath' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW_PATH ),
			'adminView' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW_ADMIN ),
			'frontendView' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW_FRONTEND ),
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
		];
	}

	// Template --------------------------

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameType( $this->name, $this->type ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingTemplate = self::findByNameType( $this->name, $this->type );

			if( isset( $existingTemplate ) && $this->id != $existingTemplate->id && 
				strcmp( $existingTemplate->name, $this->name ) == 0 && strcmp( $existingTemplate->type, $this->type ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_TEMPLATE;
	}

	// Template --------------------------

	/**
	 * @return array - Template by type
	 */
	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	/**
	 * @return Template - by name and type
	 */
	public static function findByNameType( $name, $type ) {

		return self::find()->where( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] )->one();
	}

	/**
	 * @return boolean - check whether a template exist by the provided name and type
	 */
	public static function isExistByNameType( $name, $type ) {

		$template = self::findByNameType( $name, $type );

		return isset( $template );
	}

	public static function findBySlugType( $slug, $type ) {

		return self::find()->where( 'slug=:slug AND type=:type', [ ':slug' => $slug, ':type' => $type ] )->one();
	}
}

?>