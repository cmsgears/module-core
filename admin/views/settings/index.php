<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Settings | ' . $coreProperties->getSiteTitle();

$settings		= Yii::$app->sidebar->getConfig();
?>

<div class="wrap-settings clearfix">
<?php foreach ( $settings as $setting ) { ?>

	<div class="box box-collapsible box-settings col12x6">
		<div class="box-wrap-header">
			<span id="settings-<?= $setting ?>" class="cmt-request" content="settings-<?= $setting ?>-content" cmt-controller="settings" cmt-action="getContent" action="<?= Url::toRoute( "/apix/cmgcore/settings/index?type=$setting" ) ?>" method="post">
				<span class="cmt-click btn-collapse cmti cmti-2x cmti-chevron-down"></span>
			</span>
			<span class="h5"><?= ucfirst( $setting ) ?></span>
		</div>
		<div id="settings-<?= $setting ?>-content" class="box-wrap-content clearfix"></div>
	</div>

<?php } ?>
</div>

<!-- Templates -->
<?php include_once( dirname( __FILE__ ) . "/templates/update.php" ); ?>