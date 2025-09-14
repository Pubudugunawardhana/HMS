<?php
require_once('inc/essentials.php');
require_once('inc/db_config.php');
adminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Wedding Halls</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">WEDDING HALLS</h3>
                
                <!-- Add Wedding Hall Button -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title mb-0">Manage Wedding Halls</h5>
                            <button type="button" class="btn btn-dark shadow-none" data-bs-toggle="modal" data-bs-target="#add-wedding-hall">
                                <i class="bi bi-plus-circle"></i> Add Wedding Hall
                            </button>
                        </div>
                        
                        <!-- Wedding Halls Table -->
                        <div class="table-responsive">
                            <table class="table table-hover border">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Package PDF</th>
                                        <th scope="col">Images</th>
                                        <th scope="col">Inquiries</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="wedding-halls-data">
                                    <!-- Data will be loaded here via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Wedding Hall Modal -->
    <div class="modal fade" id="add-wedding-hall" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Wedding Hall</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-wedding-hall-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hall Name *</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package PDF</label>
                                <input type="file" name="package_pdf" class="form-control shadow-none" accept=".pdf">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-control shadow-none" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Images</label>
                            <input type="file" name="images[]" class="form-control shadow-none" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Wedding Hall</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Wedding Hall Modal -->
    <div class="modal fade" id="edit-wedding-hall" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wedding Hall</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-wedding-hall-form">
                    <input type="hidden" name="wedding_hall_id" id="edit_wedding_hall_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hall Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package PDF</label>
                                <input type="file" name="package_pdf" class="form-control shadow-none" accept=".pdf">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea name="description" id="edit_description" class="form-control shadow-none" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Add More Images</label>
                            <input type="file" name="images[]" class="form-control shadow-none" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Wedding Hall</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="scripts/wedding_halls.js"></script>
    <?php require('inc/scripts.php'); ?>
</body>
</html> 