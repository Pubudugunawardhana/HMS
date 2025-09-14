<?php
class SettingsManager {
    private $conn;
    private $teamImageDir;

    public function __construct(mysqli $db, string $teamImageDir) {
        $this->conn = $db;
        $this->teamImageDir = $teamImageDir;
    }

    // General Settings
    public function getGeneralSettings(): string {
        $res = select("SELECT * FROM settings WHERE sr_no=?", [1], 'i');
        return json_encode(mysqli_fetch_assoc($res));
    }

    public function updateGeneralSettings(string $title, string $about): int {
        return update("UPDATE settings SET site_title=?, site_about=? WHERE sr_no=?", [$title, $about, 1], 'ssi');
    }

    public function toggleShutdown(int $current): int {
        $newValue = $current == 0 ? 1 : 0;
        return update("UPDATE settings SET shutdown=? WHERE sr_no=?", [$newValue, 1], 'ii');
    }

    // Contact Settings
    public function getContactDetails(): string {
        $res = select("SELECT * FROM contact_details WHERE sr_no=?", [1], 'i');
        return json_encode(mysqli_fetch_assoc($res));
    }

    public function updateContactDetails(array $data): int {
        $params = [
            $data['address'], $data['gmap'], $data['pn1'], $data['pn2'],
            $data['email'], $data['fb'], $data['insta'], $data['tw'], $data['iframe'], 1
        ];
        return update("UPDATE contact_details SET address=?, gmap=?, pn1=?, pn2=?, email=?, fb=?, insta=?, tw=?, iframe=? WHERE sr_no=?", $params, 'sssssssssi');
    }

    
    //Team Members
    public function addTeamMember(string $name, $file): string|int {
        $img = uploadImage($file, $this->teamImageDir);

        if (in_array($img, ['inv_img', 'inv_size', 'upd_failed'])) {
            return $img;
        }

        return insert("INSERT INTO team_details(name, picture) VALUES (?, ?)", [$name, $img], 'ss');
    }

    public function getTeamMembers(): string {
        $res = selectAll("team_details");
        $output = '';
        $path = ABOUT_IMG_PATH;

        while ($row = mysqli_fetch_assoc($res)) {
            $output .= <<<HTML
                <div class="col-md-2 mb-3">
                  <div class="card bg-dark text-white">
                    <img src="{$path}{$row['picture']}" class="card-img">
                    <div class="card-img-overlay text-end">
                      <button type="button" onclick="rem_member({$row['sr_no']})" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i> Delete
                      </button>
                    </div>
                    <p class="card-text text-center px-3 py-2">{$row['name']}</p>
                  </div>
                </div>
            HTML;
        }

        return $output;
    }

    public function removeTeamMember(int $id): int {
        $res = select("SELECT * FROM team_details WHERE sr_no=?", [$id], 'i');
        $img = mysqli_fetch_assoc($res);

        if (deleteImage($img['picture'], $this->teamImageDir)) {
            return delete("DELETE FROM team_details WHERE sr_no=?", [$id], 'i');
        }

        return 0;
    }
}
