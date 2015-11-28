<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../docs/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../docs/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../docs/js/jquery.min.js"></script>
<script src="../docs/js/bootstrap.min.js"></script>
<script src="../vendor/mesour/components/public/mesour.components.js"></script>
<script src="../public/mesour.selection.js"></script>
<script src="../docs/js/main.js"></script>

<style>
    .select-checkbox,
    .main-checkbox{
        height: 22px;
        width: 25px;
    }
</style>

<?php

define('SRC_DIR', __DIR__ . '/../src/');

require_once __DIR__ . '/../vendor/autoload.php';

@mkdir(__DIR__ . '/log');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');

require_once SRC_DIR . 'Mesour/UI/ISelection.php';
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

    $selection->render();

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