<div class='container'>
	<?	 if(strlen(validation_errors())>0){ ?><div class="alert alert-dismissable alert-warning"><p><?php echo validation_errors(); ?></p></div><? } ?>
	
	<form class="form-horizontal" action="<?='user/login/'.$redirect?>" method="post">
	<?=form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());?> 
	<fieldset>

	<!-- Text input-->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="email">E-mail</label>	
	  <div class="col-md-6">
	  <input id="email" name="email" type="text" placeholder="E-mail" class="form-control input-md" required="">
	
	  </div>
	</div>

	<!-- Password input-->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="password">Password</label>
	  <div class="col-md-6">
		<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="">
	
	  </div>
	</div>

	<!-- Button -->
	<div class="form-group">
	  <label class="col-md-4 control-label" for="submit"></label>
	  <div class="col-md-4">
		<button id="submit" name="submit" class="btn btn-primary">Login</button>
	  </div>
	</div>

	</fieldset>
	</form>
</div>