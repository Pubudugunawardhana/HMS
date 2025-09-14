<?php
class CarouselManager {
    private $conn;
    private $uploadDir;

    public function __construct(mysqli $db, $uploadDir) {
        $this->conn = $db;
        $this->uploadDir = $uploadDir;
    }

    public function addImage($imageFile) {
        $uploadResult = uploadImage($imageFile, $this->uploadDir);

        if (in_array($uploadResult, ['inv_img', 'inv_size', 'upd_failed'])) {
            return $uploadResult;
        }

        $query = "INSERT INTO carousel(image) VALUES (?)";
        $res = insert($query, [$uploadResult], 's');
        return $res;
    }

    public function getCarouselImages() {
        $res = selectAll('carousel');
        $path = CAROUSEL_IMG_PATH;
        $output = '';

        while ($row = mysqli_fetch_assoc($res)) {
            $output .= <<<HTML
                <div class="col-md-4 mb-3">
                  <div class="card bg-dark text-white">
                    <img src="{$path}{$row['image']}" class="card-img">
                    <div class="card-img-overlay text-end">
                      <button type="button" onclick="rem_image({$row['sr_no']})" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i> Delete
                      </button>
                    </div>
                  </div>
                </div>
            HTML;
        }

        return $output;
    }

    public function removeImage($srNo) {
        $query = "SELECT * FROM carousel WHERE sr_no=?";
        $res = select($query, [$srNo], 'i');
        $img = mysqli_fetch_assoc($res);

        if (deleteImage($img['image'], $this->uploadDir)) {
            $delQuery = "DELETE FROM carousel WHERE sr_no=?";
            return delete($delQuery, [$srNo], 'i');
        }

        return 0;
    }
}
