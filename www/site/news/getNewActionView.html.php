<?php global $g_user; ?>
<script language="javascript" type="text/javascript" src="<?php echo $this->createUriFromBase('script/tiny_mce/tiny_mce.js'); ?>"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	language : "fr",
	skin : "o2k7",
	theme : "advanced",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,forecolor,backcolor,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,insertdate,inserttime,preview",
	theme_advanced_buttons3 : "charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,fullscreen,image,cleanup,code,|,print",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	template_external_list_url : "example_template_list.js"
	
});
</script>
<script type='text/javascript' src="<?php echo $this->createUriFromBase('script/ui/ui.datepicker.js'); ?>"></script>
<form method="POST" action="<?php echo $this->createUriFromResource('?_method=POST');?>">
	<table>
		<tr>
			<td>titre : <input type="text" name="news_title" /></td>
		</tr>
		<tr>
			<td><textarea rows="15" cols="60" name="news_content"></textarea></td>
		</tr>
<?php if ($g_user->haveRight('news_edit')){ ?>
		<tr>
			<td>
				date de la publication : <input type="text" name="news_date" id="news_date" size="10" maxlength="10" />
				<script language="javascript" type="text/javascript">
					jQuery("#news_date").datepicker(jQuery.extend({}, jQuery.datepicker.regional["fr"], {     firstDay: 1,     changeFirstDay: false,    dateFormat: "dd/mm/yy",     showOn: "button",     buttonImage: "<?php echo $this->createUriFromBase('img/calendar.gif'); ?>",     buttonImageOnly: true }));
				</script>
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="news_publish" id="news_publish" /><label for="news_publish">Publication</label></td>
		</tr>
<?php } ?>
		<tr>
			<td><input type="submit" name="submit" /></td>
		</tr>		
	</table>
</form>