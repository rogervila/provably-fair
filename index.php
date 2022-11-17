<?php

use ProvablyFair\Algorithm;
use ProvablyFair\ProvablyFair;
use ProvablyFair\Seed;

require __DIR__ . '/vendor/autoload.php';

const ALGORITHM = 'sha512';
const SERVER = 'example';
const CLIENT = 'example';
const AMOUNT = 100;
const PREPEND = false;

$results = (new ProvablyFair(
    $clientSeed = new Seed($_GET['client'] ?? CLIENT),
    $serverSeed = new Seed($_GET['server'] ?? SERVER),
    $algorithm = new Algorithm($_GET['algorithm'] ?? ALGORITHM),
))->generate(
        $amount = intval($_GET['amount'] ?? AMOUNT),
        $prepend = boolval($_GET['prepend'] ?? PREPEND)
)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Provably Fair Demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Provably Fair Demo</h1>
        <hr>
        <form class="mb-5">
            <div class="row">
                <div class="col-12 col-md-3">
                    <label class="form-label" for="algorithm">Algorithm</label>
                    <select class="form-select" name="algorithm" id="algorithm">
                        <?php foreach (hash_hmac_algos() as $available_algorithm): ?>
                            <option <?php echo $available_algorithm === $algorithm->value ? 'selected' : '' ?> value="<?php echo $available_algorithm ?>"><?php echo $available_algorithm ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label" for="server">Server seed</label>
                    <input class="form-control" id="server" autocomplete="off" type="text" name="server" value="<?php echo $serverSeed->value ?>" required>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label" for="client">Client seed</label>
                    <input class="form-control" id="client" autocomplete="off" type="text" name="client" value="<?php echo $clientSeed->value ?>" required>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label" for="amount">Amount of results</label>
                    <input class="form-control" id="amount" autocomplete="off" type="number" min="0" name="amount" value="<?php echo $amount ?>" required>
                </div>
            </div>

            <div class="mt-3">
                <input type="submit" class="btn btn-primary me-2" value="Submit">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="prepend" name="prepend" <?php echo $prepend === true ? 'checked' : '' ?> >
                  <label class="form-check-label" for="prepend">Prepend original server seed result</label>
                </div>
            </div>
        </form>

        <table class="table table-responsive w-100 d-block d-md-table">
            <tr>
                <th>#</th>
                <th>Hash</th>
                <th>Result</th>
            </tr>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td>
                        <small><?php echo $result->index ?></small>
                    </td>
                    <td>
                        <pre class="m-0"><?php echo $result->hash ?></pre>
                    </td>
                    <td>
                        <strong><?php echo $result->value ?></strong>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
