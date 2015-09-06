<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Template Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $layout
 * @property string $viewPath
 * @property string $adminView
 * @property string $frontendView
 * @property string $content
 */
class Template extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'layout', 'viewPath', 'adminView', 'frontendView' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'name', 'type' ], 'required' ],
            [ [ 'id', 'description', 'layout', 'viewPath', 'adminView', 'frontendView', 'content' ], 'safe' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
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
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'layout' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LAYOUT ),
			'viewPath' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW_PATH ),
			'adminView' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW_ADMIN ),
			'frontendView' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW_FRONTEND )
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
}

?>