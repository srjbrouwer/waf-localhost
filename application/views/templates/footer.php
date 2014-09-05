		</div>
	</div>
	<div id="footer">
		<div class="container">
			<p class="muted credit">
			<?php
				if(isset($menu)){
					foreach ($menu as $menu_item){
						//left?
						if($menu_item['position']=='footer'){
							?>
								<a href="<?=$menu_item['link']?>"><?=$menu_item['name']?></a>&nbsp;&nbsp;&nbsp;
							<?
						}
					}
				}
				?>
			</p>
		</div>
	</div>
</body>
</html>