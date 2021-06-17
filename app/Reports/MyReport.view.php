<?php
use \koolreport\widgets\koolphp\Table;
?>
<html>
    <head>
    <title>My Report</title>
    </head>
    <body>
        <?php
        Table::create([
            "dataSource"=>$this->dataStore("treatment_center")
        ]);
        ?>
    </body>
</html>
