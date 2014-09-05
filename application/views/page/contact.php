<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

	<?	 if(strlen(validation_errors())>0){ ?><div class="alert alert-dismissable alert-warning"><p><?php echo validation_errors(); ?></p></div><? } ?>
  <?
  	$attributes = array('class' => 'form-horizontal');
  	echo form_open('page/contact',$attributes);
  ?>
	<fieldset>
	<!-- Text input-->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="name">Name</label>  
	  <div class="col-md-6">
	  <input id="name" name="name" type="text" placeholder="Piet Puk" class="form-control input-md" required="">
	
	  </div>
	</div>

	<!-- Text input-->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="email">E-mail</label>  
	  <div class="col-md-6">
	  <input id="email" name="email" type="text" placeholder="piet.puk@domein.nl" class="form-control input-md" required="">
	
	  </div>
	</div>

	<!-- Textarea -->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="message">Message</label>
	  <div class="col-md-4">                     
		<textarea class="form-control" id="message" name="message"></textarea>
	  </div>
	</div>

	<!-- Button -->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="send"></label>
	  <div class="col-md-4">
		<button id="send" name="send" class="btn btn-primary">Send</button>
	  </div>
	</div>

	</fieldset>
	</form>
