<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Experience</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Experience Options</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-option">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>

                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Highlight</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Price List</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="experience-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="add-option" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_experience_option_form" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Experience Option</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Title</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Highlight</label>
                                <input type="text" name="highlight" class="form-control shadow-none" placeholder="" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Image</label>
                                <input type="file" name="image" accept=".jpg,.png,.webp,.jpeg" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Price List</label>
                                <input type="file" name="pricelist" accept=".pdf" class="form-control shadow-none" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Guide Name</label>
                                    <input type="text" name="guide_name" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Guide Email</label>
                                    <input type="email" name="guide_email" class="form-control shadow-none" required>   
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Guide Phone No</label>
                                    <input type="text" name="guide_phone" class="form-control shadow-none" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="edit-experience-option" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_experience_option_form" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Experience Option</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Title</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Highlight</label>
                                <input type="text" name="highlight" class="form-control shadow-none" placeholder="" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Image (Optional - Leave empty to keep current image)</label>
                                <input type="file" name="image" accept=".jpg,.png,.webp,.jpeg" class="form-control shadow-none">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Price List (Optional - Leave empty to keep current price list)</label>
                                <input type="file" name="pricelist" accept=".pdf" class="form-control shadow-none">
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Guide Name</label>
                                    <input type="text" name="guide_name" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Guide Email</label>
                                    <input type="email" name="guide_email" class="form-control shadow-none" required>   
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Guide Phone No</label>
                                    <input type="text" name="guide_phone" class="form-control shadow-none" required>
                                </div>
                            </div>
                            <input type="hidden" name="experience_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">UPDATE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/experience.js"></script>

</body>

</html>