<login
    <?php if ($this->error == null): ?>
    success="true"
    error=""
<?php else: ?>
    success="false"
    error="<?php
    if ($this->error != null)
       print_r(Vbf_Guard::guardAnyXml($this->error->getMessage()));
    ?>"
<?php endif; ?>
    ></login>
