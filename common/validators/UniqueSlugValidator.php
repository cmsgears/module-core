<?php
namespace cmsgears\core\common\validators;

class UniqueSlugValidator extends \yii\validators\UniqueValidator {

    /**
     * @inheritdoc
     */
    public function validateAttribute( $model, $attribute ) {

        $targetClass 		= get_class( $model );
        $targetAttribute 	= $attribute;

        $query 		= $targetClass::find();
		$attribVal	= $model->$attribute;

        $query->andWhere( "$targetAttribute=:val", [ ':val' => $attribVal ] );

        if( !$model instanceof ActiveRecordInterface || $model->getIsNewRecord() ) {

            $exists = $query->exists();
        }
        else {

            $models = $query->limit( 2 )->all();
            $n 		= count( $models );

            if( $n === 1 ) {

                $exists = $models[0]->getPrimaryKey() != $model->getOldPrimaryKey();
            }
            else {

                $exists = $n > 1;
            }
        }

        if( $exists ) {

            $this->addError($model, $attribute, $this->message);
        }
    }
}