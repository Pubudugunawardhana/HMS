<?php
require_once('admin/inc/essentials.php');
require_once('admin/inc/db_config.php');

require_once 'admin/classes/Utility.php';


$settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
$values = [1];
$settings_r = mysqli_fetch_assoc(select($settings_q, $values, 'i'));

// Get all wedding halls using existing database functions
$wedding_halls = [];
try {
    $wedding_halls_query = "SELECT * FROM wedding_halls ORDER BY created_at DESC";
    $wedding_halls_result = select($wedding_halls_query, [], '');
    if ($wedding_halls_result) {
        while ($row = mysqli_fetch_assoc($wedding_halls_result)) {
            $wedding_halls[] = $row;
        }
    }
} catch (Exception $e) {
    // Table doesn't exist or other database error
    $wedding_halls = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings_r['site_title']; ?> - Weddings</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR WEDDING HALLS</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Celebrate your love amidst the awe-inspiring natural beauty of Heritance 360, an architectural gem designed
            by world renowned architect Geoffrey Bawa. Our forested sanctuary provides a stunning backdrop to your special day,
            complete with an elegant architectural venue, exquisite dining options and perfect attention paid to every detail.
            Our dedicated team of professionals will work hand-in-hand with you, ensuring your dream day turns into a cherished memory.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <?php if (empty($wedding_halls)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>No wedding halls available at the moment.</h4>
                        <p>Please check back later or contact us for more information.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($wedding_halls as $hall): ?>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-0">
                                <!-- Image Carousel -->
                                <div id="carousel-<?php echo $hall['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        <?php
                                        // Get wedding hall images using existing database functions
                                        $images = [];
                                        try {
                                            $images_query = "SELECT * FROM wedding_images WHERE wedding_id = ? ORDER BY id ASC";
                                            $images_result = select($images_query, [$hall['id']], 'i');
                                            if ($images_result) {
                                                while ($image_row = mysqli_fetch_assoc($images_result)) {
                                                    $images[] = $image_row;
                                                }
                                            }
                                        } catch (Exception $e) {
                                            // Table doesn't exist or other database error
                                            $images = [];
                                        }
                                        if (!empty($images)):
                                            foreach ($images as $index => $image):
                                        ?>
                                                <button type="button" data-bs-target="#carousel-<?php echo $hall['id']; ?>"
                                                    data-bs-slide-to="<?php echo $index; ?>"
                                                    class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </div>
                                    <div class="carousel-inner">
                                        <?php
                                        if (!empty($images)):
                                            foreach ($images as $index => $image):
                                        ?>
                                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                    <img src="<?php echo WEDDING_IMG_PATH . $image['image_path']; ?>"
                                                        class="d-block w-100" style="height: 300px; object-fit: cover;"
                                                        alt="<?php echo htmlspecialchars($hall['name']); ?>">
                                                </div>
                                            <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <div class="carousel-item active">
                                                <img src="<?php echo SITE_URL; ?>images/default-wedding.jpg"
                                                    class="d-block w-100" style="height: 300px; object-fit: cover;"
                                                    alt="Default Wedding Hall Image">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (count($images) > 1): ?>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $hall['id']; ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $hall['id']; ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($hall['name']); ?></h5>
                                    <p class="card-text text-muted">
                                        <?php echo nl2br(htmlspecialchars(substr($hall['description'], 0, 150))); ?>
                                        <?php if (strlen($hall['description']) > 150): ?>...<?php endif; ?>
                                    </p>

                                    <div class="d-flex gap-2 mb-3">
                                        <?php if ($hall['package_pdf']): ?>
                                            <a href="<?php echo WEDDING_PDF_PATH . $hall['package_pdf']; ?>"
                                                target="_blank" class="btn btn-primary btn-sm">
                                                More Details
                                            </a>
                                        <?php endif; ?>

                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#contactModal-<?php echo $hall['id']; ?>">
                                            Inquire Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Modal for each hall -->
                    <div class="modal fade" id="contactModal-<?php echo $hall['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Contact for <?php echo htmlspecialchars($hall['name']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form class="contact-form" data-hall-id="<?php echo $hall['id']; ?>">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name-<?php echo $hall['id']; ?>" class="form-label">Your Name *</label>
                                            <input type="text" class="form-control" id="name-<?php echo $hall['id']; ?>"
                                                name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email-<?php echo $hall['id']; ?>" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="email-<?php echo $hall['id']; ?>"
                                                name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="message-<?php echo $hall['id']; ?>" class="form-label">Message *</label>
                                            <textarea class="form-control" id="message-<?php echo $hall['id']; ?>"
                                                name="message" rows="4" required
                                                placeholder="Please provide details about your wedding requirements, preferred date, number of guests, etc."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Handle contact form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const contactForms = document.querySelectorAll('.contact-form');

            contactForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const hallId = this.getAttribute('data-hall-id');
                    formData.append('wedding_id', hallId);
                    formData.append('action', 'submit_inquiry');

                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
                    submitBtn.disabled = true;

                    fetch('ajax/wedding_inquiry.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.status == 'success');
                            if (data.status == 'success') {
                                alert('success', 'Thank you! Your message has been sent successfully. We will contact you soon.');
                                this.reset();
                                const modal = this.closest('.modal');
                                const modalInstance = bootstrap.Modal.getInstance(modal);
                                modalInstance.hide();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        })
                        .finally(() => {
                            // Reset button state
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        });
                });
            });
        });
    </script>

    <?php require('inc/footer.php'); ?>

</body>

</html>