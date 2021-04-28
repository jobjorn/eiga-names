<?php


include("core.php");

$active['import'] = 'class="active"';


$page_title = "Import";
include("header.php");

?>
<div class="container">
    <div class="col-md-12">
        <h1>Letterboxd Import</h1>
        <p>Export your data from Letterboxd and paste the contents of
            <pre>ratings.csv</pre> in the field below. It should be formatted like this sample:</p>
        <pre>Date,Name,Year,Letterboxd URI,Rating
2019-10-06,"Three Billboards Outside Ebbing, Missouri",2017,https://boxd.it/ceBS,4
2020-11-08,My Octopus Teacher,2020,https://boxd.it/prk2,3
2020-11-11,A Secret Love,2020,https://boxd.it/pE6i,3</pre>
        <form action="<?php echo $root_uri; ?>do_import/" method="post">
            <p><textarea name="ratings" id="ratings" style="width: 100%;"></textarea></p>
            <p><input type="submit" value="Submit"></p>
        </form>
    </div>
</div>



<?php

include("footer.php");
