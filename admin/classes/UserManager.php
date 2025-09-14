<?php
class UserManager {
    private $conn;
    private $userImgPath;

    public function __construct(mysqli $db, string $userImgPath) {
        $this->conn = $db;
        $this->userImgPath = $userImgPath;
    }

    private function generateUserRow($row, $index): string {
        $verified = $row['is_verified']
            ? "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>"
            : "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";

        $del_btn = $row['is_verified']
            ? ""
            : "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
                  <i class='bi bi-trash'></i> 
               </button>";

        $status_btn = $row['status']
            ? "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>"
            : "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>inactive</button>";

        $date = date("d-m-Y", strtotime($row['datentime']));

        return <<<HTML
            <tr>
              <td>{$index}</td>
              <td>
                <img src="{$this->userImgPath}{$row['profile']}" width="55px"><br>{$row['name']}
              </td>
              <td>{$row['email']}</td>
              <td>{$row['phonenum']}</td>
              <td>{$row['address']} | {$row['pincode']}</td>
              <td>{$row['dob']}</td>
              <td>{$verified}</td>
              <td>{$status_btn}</td>
              <td>{$date}</td>
              <td>{$del_btn}</td>
            </tr>
        HTML;
    }

    public function getAllUsers(): string {
        $res = selectAll('user_cred');
        $data = "";
        $i = 1;

        while ($row = mysqli_fetch_assoc($res)) {
            $data .= $this->generateUserRow($row, $i++);
        }

        return $data;
    }

    public function toggleUserStatus(int $userId, int $value): int {
        return update("UPDATE user_cred SET status=? WHERE id=?", [$value, $userId], 'ii');
    }

    public function removeUnverifiedUser(int $userId): int {
        return delete("DELETE FROM user_cred WHERE id=? AND is_verified=?", [$userId, 0], 'ii');
    }

    public function searchUsers(string $name): string {
        $res = select("SELECT * FROM user_cred WHERE name LIKE ?", ["%{$name}%"], 's');
        $data = "";
        $i = 1;

        while ($row = mysqli_fetch_assoc($res)) {
            $data .= $this->generateUserRow($row, $i++);
        }

        return $data;
    }
}
