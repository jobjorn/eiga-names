<?php


include("core.php");

$active['import'] = 'class="active"';


$page_title = "Import";
include("header.php");

?>
<div class="container">
    <div class="col-md-12">
        <h1>Namnimport</h1>
        <p>Ett namn per rad:</p>
        <form action="<?php echo $root_uri; ?>do_import/" method="post">
            <p><textarea name="names" id="names" style="width: 100%;"></textarea></p>
            <p><input type="submit" value="Submit"></p>
        </form>
    </div>
</div>



<?php

include("footer.php");
