<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Delete User";

// Sidebar
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'email' )->textInput( [ 'readonly'=>'true' ] ) ?>
    	<?= $form->field( $model, 'username' )->textInput( [ 'readonly'=>'true' ] ) ?>
    	<?= $form->field( $model, 'status' )->dropDownList( $status, [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $siteMember, 'roleId' )->dropDownList( $roleMap, [ 'disabled'=>'true' ] )  ?>
		<?= $form->field( $model, 'gender' )->dropDownList( $genderMap, [ 'disabled'=>'true' ] )  ?>
		<?= $form->field( $model, 'firstName' )->textInput( [ 'readonly'=>'true' ] ) ?>
		<?= $form->field( $model, 'lastName' )->textInput( [ 'readonly'=>'true' ] ) ?>
		<?= $form->field( $model, 'phone' )->textInput( [ 'readonly'=>'true' ] ) ?>
		<?= $form->field( $model, 'newsletter' )->checkbox( [ 'readonly'=>'true' ] ) ?>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>