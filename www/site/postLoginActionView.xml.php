<?php
if ($this->error != null)
{
	$errorMessage = Vbf_Guard::guardAnyXml($this->error->getMessage());
}
?>
<login
<?php if ($this->error == null): ?>
	success="true"
	error=""
<?php else: ?>
	success="false"
	error="<?php $errorMessage; ?>"
<?php endif; ?>
/>
