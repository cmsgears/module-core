<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

class JsonModel extends \yii\base\Model {

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

    // Constructor and Initialisation ------------------------------

    public function __construct( $jsonData = false, $config = [] ) {

        if( $jsonData ) {

            if( is_object( $jsonData ) ) {

                $this->copyFromObject( $jsonData );
            }
            else if( is_array( $jsonData ) ) {

                $this->copyFromArray( $jsonData );
            }
            else {

                $this->setData( json_decode( $jsonData, true ) );
            }
        }

        parent::__construct( $config );
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    // yii\base\Model ---------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Validators ----------------------------

    //JsonModel ------------------------------

    private function setData( $data ) {

        foreach( $data as $key => $value ) {

            $this->{ $key } = $value;
        }
    }

    private function copyFromObject( $object ) {

        $attributes	= get_object_vars( $object );

        foreach ( $attributes as $key => $value ) {

            $this->{ $key } = $value;
        }
    }

    private function copyFromArray( $arr ) {

        foreach ( $arr as $key => $value ) {

            $this->{ $key } = $value;
        }
    }

    public function getData() {

        return json_encode( $this );
    }
}
