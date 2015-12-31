<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Attribute Entity
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $value
 */
abstract class Attribute extends CmgModel {
	
	const VALUE_TYPE_TEXT	= 'text';
	const VALUE_TYPE_FLAG	= 'flag';

	// Instance Methods --------------------------------------------

	public function getLabel() {

		$label  = preg_split( "/_/", $this->name );
		$label	= join( " ", $label );
		$label	= ucwords( $label );

		return $label;
	}

	public function getFieldInfo() {

		switch( $this->valueType ) {

			case self::VALUE_TYPE_TEXT: {

				return [ 'label' => $this->getLabel(), 'name' => $this->name, 'value' => $this->value ];
			}
			case self::VALUE_TYPE_FLAG: {

				$value = Yii::$app->formatter->asBoolean( $this->value );

				return [ 'label' => $this->getLabel(), 'name' => $this->name, 'value' => $value ];
			}
		}
	}

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->parentId, $this->parentType, $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->parentId, $this->parentType, $this->type, $this->name );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id && 
				$existingConfig->parentId == $this->parentId && strcmp( $existingConfig->parentType, $this->parentType ) == 0 && 
				strcmp( $existingConfig->name, $this->name ) == 0 && $existingConfig->type == $this->type ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// ModelAttribute --------------------

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return array - ModelAttribute by type
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->all();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @return ModelAttribute - by name
	 */
	public static function findByName( $parentId, $parentType, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name', [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return ModelAttribute - by type and name
	 */
	public static function findByTypeName( $parentId, $parentType, $type, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type AND name=:name', 
				[ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return boolean - Check whether attribute exist by type and name
	 */
	public static function isExistByTypeName( $parentId, $parentType, $type, $name ) {

		$config = self::findByTypeName( $parentId, $parentType, $type, $name );

		return isset( $config );
	}
}

?>