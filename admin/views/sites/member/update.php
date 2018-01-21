<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Site | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-site', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Member Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row row-inline">
						<div class="col col2">
							<div class="site-member " >
								<?= $form->field( $user, 'username' ) ?>
								<?= $form->field( $model, 'siteId' )->hiddenInput( ['value'=> $siteId])->label( false )  ?>
								<?= $form->field( $model, 'roleId' )->dropDownList( $roleMap, [ 'class' => 'cmt-select' ] ) ?>
								<?= $form->field( $model, 'userId' )->hiddenInput()->label( false )  ?>
								
								<span class=""></span>
							</div>
							<div class="filler-height"></div>	
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
