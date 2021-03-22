<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Site Member | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-member', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<div class="element-40">Email</div>
							<div class="element-60"><?= $model->user->email ?></div>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'roleId' )->dropDownList( $roleMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'popular' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
