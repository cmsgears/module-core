<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\cms\admin\models\forms\WidgetForm;

use cmsgears\core\common\behaviors\AuthorBehavior;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Role Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $data
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class ObjectData extends CmgEntity {

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	public function generateJsonFromObject( $dataObject ) {

		$data		= json_encode( $dataObject );
		$this->data	= $data;
	}

	public function generateObjectFromJson() {

		$obj 	= json_decode( $this->data, 'true' );

		return (object)$obj;
	}

	public function getTemplate() {

		return $this->hasOne( Template::className(), [ 'id' => 'templateId' ] );
	}

	public function getTemplateName() {

		$template = $this->template;

		if( isset( $template ) ) {

			return $template->name;
		}

		return '';
	}
	
	public function getWidgetForm() {
		
		$form = new WidgetForm();		
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
			AuthorBehavior::className(),
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

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'templateId', 'description', 'data' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'type' ], 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0 ],
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
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'templateId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// ObjectData ------------------------

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

			$existingObj = self::findByNameType( $this->name, $this->type );

			if( isset( $existingObj ) && $this->id != $existingObj->id && 
				strcmp( $existingObj->name, $this->name ) == 0 && strcmp( $existingObj->type, $this->type ) == 0 ) {

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

		return CoreTables::TABLE_OBJECT_DATA;
	}

	// ObjectData ------------------------

	/**
	 * @return ObjectData - by name and type
	 */
	public static function findByNameType( $name, $type ) {

		return self::find()->where( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] )->one();
	}

	/**
	 * @return boolean - check whether a object exist by the provided name and type
	 */
	public static function isExistByNameType( $name, $type ) {

		$obj = self::findByNameType( $name, $type );

		return isset( $obj );
	}
}

?>