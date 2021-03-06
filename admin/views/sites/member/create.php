<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Site Member | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-member', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Member Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="box-content mapper mapper-auto">
					<div class="auto-fill auto-fill-basic">
						<div class="auto-fill-source row row-medium" cmt-app="core" cmt-controller="member" cmt-action="autoSearch" action="core/sites/member/auto-search?sid=<?= $siteId ?>" cmt-keep cmt-custom>
							<div class="relative">
								<div class="auto-fill-search clearfix">
									<div class="frm-icon-element icon-right">
										<span class="icon cmti cmti-search"></span>
										<input class="cmt-key-up auto-fill-text search-name" type="text" name="name" placeholder="Username" autocomplete="off" />
									</div>
								</div>
								<div class="auto-fill-items-wrap">
									<ul class="auto-fill-items vnav"></ul>
								</div>
							</div>
						</div>
						<div class="filler-height filler-height-large"></div>
						<div class="site-member <?= empty( $model->name ) ? 'hidden-easy' : null ?>">
							<div class="row max-cols-100">
								<div class="col col2">
									<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] )->label( 'User' ) ?>
								</div>
								<div class="col col2">
									<?= $form->field( $model, 'siteId', [ 'errorOptions' => [ 'class' => 'help-block hidden' ] ] )->hiddenInput( [ 'value'=> $siteId ] )->label( false )  ?>
									<?= $form->field( $model, 'userId', [ 'errorOptions' => [ 'class' => 'help-block hidden' ] ] )->hiddenInput()->label( false )  ?>
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
							<div class="filler-height"></div>
							<div class="row row-inline">
								<?php if( $model->hasErrors() ) { ?>
								<span class="error">Site member already exist.</span>
								<?php } ?>
							</div>
						</div>
						<div class="filler-height"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
