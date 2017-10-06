<?php
namespace cmsgears\core\common\data;

// Yii Imports
use Yii;
use yii\base\InvalidParamException;

class ActiveDataProvider extends \yii\data\ActiveDataProvider {

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount() {

		// TODO: Use stats table to get row count to avoid expensive full table scan query
		return parent::prepareTotalCount();
    }

	public function setPagination( $value ) {

		$pagination	= null;

		if( is_array( $value ) ) {

			$config = [ 'class' => Pagination::className() ];

			if( $this->id !== null ) {

				$config[ 'pageParam' ]		= $this->id . '-page';
				$config[ 'pageSizeParam' ]	= $this->id . '-per-page';
			}

			$pagination = Yii::createObject( array_merge( $config, $value ) );
		}
		else if( $value instanceof Pagination || $value === false ) {

			$pagination = $value;
		}
		else {

			throw new InvalidParamException('Only Pagination instance, configuration array or false is allowed.' );
		}

		parent::setPagination( $pagination );
	}
}
