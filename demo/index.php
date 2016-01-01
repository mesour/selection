<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
      integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>

<link rel="stylesheet" href="../public/mesour.selection.css">

<script src="../vendor/mesour/components/public/mesour.components.js"></script>
<script src="../public/mesour.selection.js"></script>

<?php

define('SRC_DIR', __DIR__ . '/../src/');

require_once __DIR__ . '/../vendor/autoload.php';

@mkdir(__DIR__ . '/log');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');

require_once SRC_DIR . 'Mesour/Selection/ISelection.php';
require_once SRC_DIR . 'Mesour/UI/Selection.php';

?>

<hr>

<div class="container">
    <h2>Basic functionality</h2>

    <hr>

    <?php

    $selection = new \Mesour\UI\Selection('test');

    $items = array(
        1 => 'active',
        2 => 'inactive',
        3 => 'inactive',
        4 => 'active',
        5 => 'inactive',
    );

    $selection->setItems($items);

    $selection->addStatus('active', 'Active');

    $selection->addStatus('inactive', 'Inactive');

    echo $selection->render();

    echo '<br><br><br><br><br><br>';

    foreach($items as $id => $status) {
        $selection->renderItem($id);
        echo " ID: $id, Status: $status";
        echo "<br><br>";
    }

    ?>
</div>

<hr>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>