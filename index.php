<?php

require(__DIR__ . '/vendor/autoload.php');

define('ALGO', 'sha512');
define('SERVER', 'example');
define('CLIENT', 'example');
define('AMOUNT', 100);

$algo = new \ProvablyFair\Algorithm(ALGO);

$system = new \ProvablyFair\System($algo);

$serverSeed = new \ProvablyFair\Seed(isset($_GET['server']) ? $_GET['server'] : SERVER);
$clientSeed = new \ProvablyFair\Seed(isset($_GET['client']) ? $_GET['client'] : CLIENT);
$resultAmount = isset($_GET['amount']) ? $_GET['amount'] : AMOUNT;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Provably Fair Demo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <h1 class="mt-5">Provably Fair Demo</h1>
        <hr>
        <form class="mb-5">
            <div class="row">
                <div class="form-group col">
                    <label class="d-block" for="algo">Algorithm</label>
                    <input id="algo" readonly disabled autocomplete="off" type="text" name="algo" value="<?php echo $algo->getValue() ?>">
                </div>

                <div class="form-group col">
                    <label class="d-block" for="server">Server seed</label>
                    <input id="server" autocomplete="off" type="text" name="server" value="<?php echo $serverSeed->getValue() ?>" required>
                </div>

                <div class="form-group col">
                    <label class="d-block" for="client">Client seed</label>
                    <input id="client" autocomplete="off" type="text" name="client" value="<?php echo $clientSeed->getValue() ?>" required>
                </div>

                <div class="form-group col">
                    <label class="d-block" for="amount">Amount of results</label>
                    <input id="amount" autocomplete="off" type="number" min="0" name="amount" value="<?php echo $resultAmount ?>" required>
                </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Submit">
        </form>

        <table class="table table-responsive w-100 d-block d-md-table">
            <tr>
                <th>#</th>
                <th>Hash</th>
                <th>Result</th>
            </tr>
            <?php for ($result = $resultAmount; $result > 0; $result--) : ?>
                <?php $serverSeed = $system->generateServerSeed($serverSeed); ?>
                <tr>
                    <td>
                        <small><?php echo $result ?></small>
                    </td>
                    <td>
                        <pre class="m-0"><?php echo $serverSeed->getValue() ?></pre>
                    </td>
                    <td>
                        <strong><?php echo $system->calculate($serverSeed, $clientSeed) ?></strong>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4739670084306482" crossorigin="anonymous"></script>
</body>
</html>
