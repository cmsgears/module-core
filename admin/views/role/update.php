<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Role | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-role', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'slug' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'homeUrl' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'adminUrl' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'group', [ 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'group-target' => 'group-permission' ], 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if( count( $permissions ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Assign <?= ucfirst( $type ) ?> Permissions</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row padding padding-small-v">
						<?php
							$modelPermissions = $model->getPermissionsIdList();

							foreach ( $permissions as $permission ) {
						?>
								<div class="col col4">
						<?php
								if( in_array( $permission[ 'id' ], $modelPermissions ) ) {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $permission[ 'id' ] ?>" checked /><?= $permission[ 'name' ] ?>
						<?php
								}
								else {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $permission[ 'id' ] ?>" /><?= $permission[ 'name' ] ?>
						<?php
								}
						?>
								</div>
						<?php
							}
						?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if( count( $spermissions ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Assign System Permissions</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row padding padding-small-v">
						<?php
							$modelPermissions = $model->getPermissionsIdList();

							foreach ( $spermissions as $permission ) {
						?>
								<div class="col col4">
						<?php
								if( in_array( $permission[ 'id' ], $modelPermissions ) ) {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $permission[ 'id' ] ?>" checked /><?= $permission[ 'name' ] ?>
						<?php
								}
								else {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $permission[ 'id' ] ?>" /><?= $permission[ 'name' ] ?>
						<?php
								}
						?>
								</div>
						<?php
							}
						?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
