<?php
require_once('admin/inc/essentials.php');
require_once('admin/inc/db_config.php');

require_once 'admin/classes/Utility.php';


$settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
$values = [1];
$settings_r = mysqli_fetch_assoc(select($settings_q, $values, 'i'));

$experience_options = [];
try {
    $experience_options_query = "SELECT * FROM experience_options WHERE status = 1 ORDER BY created_at DESC";
    $experience_options_result = select($experience_options_query, [], '');
    if ($experience_options_result) {
        while ($row = mysqli_fetch_assoc($experience_options_result)) {
            $experience_options[] = $row;
        }
    }
} catch (Exception $e) {
    $experience_options = [];
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
        <h2 class="fw-bold h-font text-center">Experiences AT <?php echo $settings_r['site_title']; ?></h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">There are plenty of things to do in Sigiriya and the Cultural Triangle. <br/>Browse a selection of exciting activities available at the hotel which will keep you active for days.</p>
    </div>

    <div class="container">
        <div class="row">
            <?php if (empty($experience_options)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>No wedding halls available at the moment.</h4>
                        <p>Please check back later or contact us for more information.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($experience_options as $option): ?>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-0">
                                <div>
                                    <img src="./images/experience/<?php echo $option['image']; ?>" 
                                        class="d-block w-100" style="height: 300px; object-fit: cover;" 
                                        alt="Default Wedding Hall Image">
                                </div>
                                
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($option['name']); ?></h5>
                                    <p class="card-text text-muted">
                                        <?php echo nl2br(htmlspecialchars(substr($option['description'], 0, 150))); ?>
                                        <?php if (strlen($option['description']) > 150): ?>...<?php endif; ?>
                                    </p>
                                    
                                    <div class="d-flex gap-2 mb-3 justify-content-center">
                                        <a href="experience_details.php?id=<?php echo $option['id']; ?>" 
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