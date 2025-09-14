<?php
class FeatureFacilityManager {
    private $conn;
    private $facilityDir;

    public function __construct(mysqli $db, $facilityDir) {
        $this->conn = $db;
        $this->facilityDir = $facilityDir;
    }

    // ------------------ FEATURES ------------------

    public function addFeature($name) {
        return insert("INSERT INTO features(name) VALUES (?)", [$name], 's');
    }

    public function getFeatures() {
        $res = selectAll('features');
        $i = 1;
        $html = '';

        while ($row = mysqli_fetch_assoc($res)) {
            $html .= <<<HTML
                <tr>
                  <td>{$i}</td>
                  <td>{$row['name']}</td>
                  <td>
                    <button type="button" onclick="rem_feature({$row['id']})" class="btn btn-danger btn-sm shadow-none">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </td>
                </tr>
            HTML;
            $i++;
        }

        return $html;
    }

    public function removeFeature($id) {
        $check = select('SELECT * FROM room_features WHERE features_id=?', [$id], 'i');
        if (mysqli_num_rows($check) === 0) {
            return delete('DELETE FROM features WHERE id=?', [$id], 'i');
        }
        return 'room_added';
    }

    // ------------------ FACILITIES ------------------

    public function addFacility($file, $name, $desc) {
        $upload = uploadSVGImage($file, $this->facilityDir);

        if (in_array($upload, ['inv_img', 'inv_size', 'upd_failed'])) {
            return $upload;
        }

        return insert("INSERT INTO facilities(icon, name, description) VALUES (?, ?, ?)", [$upload, $name, $desc], 'sss');
    }

    public function getFacilities() {
        $res = selectAll('facilities');
        $i = 1;
        $html = '';
        $path = FACILITIES_IMG_PATH;

        while ($row = mysqli_fetch_assoc($res)) {
            $html .= <<<HTML
                <tr class='align-middle'>
                  <td>{$i}</td>
                  <td><img src="{$path}{$row['icon']}" width="100px"></td>
                  <td>{$row['name']}</td>
                  <td>{$row['description']}</td>
                  <td>
                    <button type="button" onclick="rem_facility({$row['id']})" class="btn btn-danger btn-sm shadow-none">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </td>
                </tr>
            HTML;
            $i++;
        }

        return $html;
    }

    public function removeFacility($id) {
        $check = select('SELECT * FROM room_facilities WHERE facilities_id=?', [$id], 'i');
        if (mysqli_num_rows($check) === 0) {
            $res = select("SELECT * FROM facilities WHERE id=?", [$id], 'i');
            $img = mysqli_fetch_assoc($res);

            if (deleteImage($img['icon'], $this->facilityDir)) {
                return delete("DELETE FROM facilities WHERE id=?", [$id], 'i');
            }
            return 0;
        }
        return 'room_added';
    }
}
