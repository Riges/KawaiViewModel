
<div id="news">
	<?php foreach($this->news as $new):?>
		<div class="news-head">
			<img style="float:left" src="<?php echo $this->createUriFromBase('img/#SKIN#/puce.png'); ?>" alt="puce" />
			<a style="float:left" href="<?php echo $this->createUriFromModule("", true)."/".date("Y", $new->unixDate)."/".date("m", $new->unixDate)."/".date("d", $new->unixDate).'/'.$new->news_title_url.'/' ; ?>"><?php echo $new->news_title ; ?></a><img src="<?php echo $this->createUriFromBase('img/#SKIN#/news-knb.png'); ?>" alt="knb" style="margin-right:10px; margin-top:5px;float: right;" />
		</div>
		<div class="news-content">
			<div class="text"><? echo $new->news_content ; ?></div>
		</div>
		<div class="news-create"><a href="<?php echo $this->createUriFromBase('users/').$new->user_login ; ?>"><? echo $new->user_full_name ; ?></a> at <? echo date("d/m/Y", $new->unixDate) ; ?></div>
	<br /><br />
	<?php endforeach; ?>
</div>