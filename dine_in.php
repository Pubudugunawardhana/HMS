<?php
require_once('admin/inc/essentials.php');
require_once('admin/inc/db_config.php');

require_once 'admin/classes/Utility.php';


$settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
$values = [1];
$settings_r = mysqli_fetch_assoc(select($settings_q, $values, 'i'));

$dine_in_options = [];
try {
    $dine_in_options_query = "SELECT * FROM dine_in_options WHERE status = 1 ORDER BY created_at DESC";
    $dine_in_options_result = select($dine_in_options_query, [], '');
    if ($dine_in_options_result) {
        while ($row = mysqli_fetch_assoc($dine_in_options_result)) {
            $dine_in_options[] = $row;
        }
    }
} catch (Exception $e) {
    $dine_in_options = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings_r['site_title']; ?> - Dine In</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">DINING AT <?php echo $settings_r['site_title']; ?></h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-1">
            We, at Heritance Hotels and Resorts believe in the fine art of gastronomy and endeavours to create defining dishes from fresh produce and healthy ingredients procured from local markets. As a result of this meticulous preparation, the ingredients used in the creation of meals guests partake in, whether in the chilly inclines of the hill country or down by the coast, are locally sourced whenever possible.

            <br><br>

            From farmers who grow them without preservatives and fishermen who bring in a brand new catch every morning, a plate of food at a Heritance restaurant tells the tale of an evocative culinary journey.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <?php if (empty($dine_in_options)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>No wedding halls available at the moment.</h4>
                        <p>Please check back later or contact us for more information.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($dine_in_options as $option): ?>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-0" style="text-align: center;">
                                <div>
                                    <img src="./images/dine_in/<?php echo $option['image']; ?>"
                                        class="d-block w-100" style="height: 300px; object-fit: cover;"
                                        alt="Default Wedding Hall Image">
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($option['name']); ?></h5>
                                    <p class="card-text text-muted">
                                        Type of Dine-In: <?php echo htmlspecialchars($option['type_of_dine_in']); ?>
                                    </p>
                                    <p class="card-text text-muted">
                                        Location: <?php echo htmlspecialchars($option['location']); ?>
                                    </p>
                                    <p class="card-text text-muted">
                                        Open Hours <?php echo htmlspecialchars($option['open_hours']); ?>
                                    </p>
                                    <div class="d-flex gap-2 mb-3 justify-content-center">
                                        <a href="dine_in_details.php?id=<?php echo $option['id']; ?>"
                                            class="btn btn-primary btn-sm">
                                            More Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

</body>

</html>