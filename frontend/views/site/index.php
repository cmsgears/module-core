<?php
use cmsgears\widgets\cms\Blog;

$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteName();
?>
<div id="home" class="common-module">
	<div class="module1">
		<div class="texture"></div>
	</div>
</div>

<!--- Recent Post ----->
<div id="recentpost" class="common-module module2 clearfix">
	<div class="recent-inner-wrapper clearfix">
		<h1>Recent Post</h1>
		<div class="row clearfix">
			<?= Blog::widget( [ 'options' => [ 'class' => 'blog-posts' ] ] ); ?>
		</div>
	</div>
</div>

<div id="contactus" class="common-module">
	<div class="module3">
		<div class="contact-block">
			<form id="frm-contact" group="0" key="5" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl("apix/cmgforms/site/contact"); ?>"" method="post">
				<h1>Write to Us</h1>
				<div class="row clearfix">
					<div class="col col3 left">
						<input type="text" name="Contact[name]" placeholder="Name">
						<span class="form-error" formError="name"></span>
					</div>
					<div class="col col3 left">
						<input type="text" name="Contact[email]" placeholder="Email">
						<span class="form-error" formError="email"></span>
					</div>
					<div class="col col3 left">
						<input type="text" name="Contact[subject]" placeholder="Subject">
						<span class="form-error" formError="subject"></span>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col col6x4">
						<textarea rows="5" cols="30" name="Contact[message]" placeholder="Message"></textarea>
						<span class="form-error" formError="message"></span>
					</div>
					<div class="col col3">
						<span></span>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col col6x4">
						<input class="right" type="submit" value="Submit">
					</div>
					<div class="col col3">
						<span></span>
					</div>
				</div>
				<div class="row clearfix">			
				</div>
				<div class="spinner"></div>
				<div class="frm-message"></div>
			</form>
		</div>	
	</div>
</div>