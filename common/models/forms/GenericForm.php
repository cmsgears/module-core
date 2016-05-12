<?php
namespace cmsgears\core\common\models\forms;

class GenericForm extends \yii\base\Model {

	public $active;

	public $fields;

	public $attribs;

	public $captcha;

    public function __construct( $config = [] ) {

		$this->fields	= $config[ 'fields' ];
		$this->attribs	= [];
		$fields			= $this->fields;

		unset( $config[ 'fields' ] );

		foreach ( $fields as $key => $field ) {

			if( isset( $field->value ) ) {

				$this->__set( $key, $field->value );
			}
			else {

				$this->__set( $key, null );
			}

			$this->attribs[]	= $key;
		}

		parent::__construct( $config );
    }

	// Instance Methods --------------------------------------------

	// yii\base\Object

	public function __set( $name, $value ) {

        $setter 	= 'set' . $name;

        if( method_exists( $this, $setter ) ) {

            $this->$setter( $value );
        }
        else {

            $this->$name	= $value;
        }
	}

	// yii\base\Model

 	public function rules() {

		// Prepare validators list
		$validators		= [];
		$fields			= $this->fields;

		foreach ( $fields as $key => $field ) {

			if( isset( $field->validators ) ) {

				$fieldValidators = preg_split( "/,/", $field->validators );

				foreach ( $fieldValidators as $validator ) {

					if( !isset( $validators[ $validator ] ) ) {

						$validators[ $validator ]	= [];
					}

					if( strlen( $validator ) > 0 ) {

						$validators[ $validator ][]	= $field->name;
					}
				}
			}
		}

        $rules = [
            [ 'captcha', 'captcha', 'captchaAction' => '/forms/site/captcha', 'on' => 'captcha' ],
            [ $this->attribs, 'safe' ]
        ];

		foreach ( $validators as $key => $value ) {

			if( count( $value ) > 0 ) {

				$rules[]	= [ $value, $key ];
			}
		}

		return $rules;
    }

    public function attributeLabels() {

		$fields	= $this->fields;
		$labels	= [];

		foreach ( $fields as $key => $field ) {

			if( isset( $field->label ) ) {

				$labels[ $key ] = $field->label;
			}
			else {

				$labels[ $key ] = $key;
			}
		}

        return $labels;
    }

	// Generic Form

	/**
	 * The method collect the list of class members and their values using reflection.
	 * return array - list of class members and their value
	 */
	public function getFormAttributes( $classPath = null ) {

		if( !isset( $classPath ) ) {

			$classPath	= 'cmsgears\core\common\models\forms\GenericForm';
		}

	  	$refclass	= new \ReflectionClass( $classPath );
		$attribArr	= array();

	  	foreach ( $refclass->getProperties() as $property ) {

			$name = $property->name;

	    	if ( $property->class == $refclass->name ) {

				$attribArr[ $name ] = $this->$name;
			}
	  	}

		return $attribArr;
	}
}

?>