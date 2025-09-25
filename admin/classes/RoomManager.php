<?php

class RoomManager {
    private $conn;
    private $uploadDir;

    //Encapsulation
    //Each class encapsulates related properties and methods, 
    //hiding internal details and exposing only necessary functionality.

    public function __construct(mysqli $db, string $uploadDir) {
        $this->conn = $db;
        $this->uploadDir = $uploadDir;
    }

    public function addRoom($data, $features, $facilities, $meal_plans): int {
        $query = "INSERT INTO rooms (name, area, price, quantity, adult, children, description)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [$data['name'], $data['area'], $data['price'], $data['quantity'], $data['adult'], $data['children'], $data['desc']];

        $inserted = insert($query, $params, 'siiiiis');
        if (!$inserted) return 0;

        $roomId = mysqli_insert_id($this->conn);

        // Facilities
        $stmt = $this->conn->prepare("INSERT INTO room_facilities (room_id, facilities_id) VALUES (?, ?)");
        foreach ($facilities as $f) {
            $stmt->bind_param('ii', $roomId, $f);
            $stmt->execute();
        }
        $stmt->close();

        // Features
        $stmt = $this->conn->prepare("INSERT INTO room_features (room_id, features_id) VALUES (?, ?)");
        foreach ($features as $f) {
            $stmt->bind_param('ii', $roomId, $f);
            $stmt->execute();
        }
        $stmt->close();

        // Meal Plans
        $stmt = $this->conn->prepare("INSERT INTO room_meal_plans (room_id, meal_plan_id, price_modifier) VALUES (?, ?, ?)");
        foreach ($meal_plans as $meal_plan_id => $price) {
            $mp_id = (int)$meal_plan_id;
            $mp_price = floatval($price);
            $stmt->bind_param('iid', $roomId, $mp_id, $mp_price);
            $stmt->execute();
        }
        $stmt->close();

        return 1;
    }

    public function getAllRooms(): string {
        $res = select("SELECT * FROM rooms WHERE removed = 0", [], '');
        $html = "";
        $i = 1;

        while ($row = mysqli_fetch_assoc($res)) {
            $statusBtn = $row['status'] == 1
                ? "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>"
                : "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";

            $html .= <<<HTML
                <tr class='align-middle'>
                  <td>{$i}</td>
                  <td>{$row['name']}</td>
                  <td>{$row['area']} sq. ft.</td>
                  <td>
                    <span class='badge rounded-pill bg-light text-dark'>Adult: {$row['adult']}</span><br>
                    <span class='badge rounded-pill bg-light text-dark'>Children: {$row['children']}</span>
                  </td>
                  <td>LKR{$row['price']}</td>
                  <td>{$row['quantity']}</td>
                  <td>{$statusBtn}</td>
                  <td>
                    <button type='button' onclick='edit_details({$row['id']})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
                      <i class='bi bi-pencil-square'></i> 
                    </button>
                    <button type='button' onclick="room_images({$row['id']},'{$row['name']}')" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
                      <i class='bi bi-images'></i> 
                    </button>
                    <button type='button' onclick='remove_room({$row['id']})' class='btn btn-danger shadow-none btn-sm'>
                      <i class='bi bi-trash'></i> 
                    </button>
                  </td>
                </tr>
            HTML;
            $i++;
        }

        return $html;
    }

    public function getRoomDetails(int $roomId): string {
        $room = mysqli_fetch_assoc(select("SELECT * FROM rooms WHERE id=?", [$roomId], 'i'));

        $features = array_map(fn($r) => $r['features_id'], mysqli_fetch_all(select("SELECT * FROM room_features WHERE room_id=?", [$roomId], 'i'), MYSQLI_ASSOC));
        $facilities = array_map(fn($r) => $r['facilities_id'], mysqli_fetch_all(select("SELECT * FROM room_facilities WHERE room_id=?", [$roomId], 'i'), MYSQLI_ASSOC));
        $meal_plans = array_map(fn($r) => $r['price_modifier'], mysqli_fetch_all(select("SELECT * FROM room_meal_plans WHERE room_id=?", [$roomId], 'i'), MYSQLI_ASSOC));
        return json_encode(['roomdata' => $room, 'features' => $features, 'facilities' => $facilities,'meal_plans' => $meal_plans]);
    }

    public function editRoom($data, $features, $facilities, $meal_plans): int {

        $query = "UPDATE rooms SET name=?, area=?, price=?, quantity=?, adult=?, children=?, description=? WHERE id=?";
        $params = [$data['name'], $data['area'], $data['price'], $data['quantity'], $data['adult'], $data['children'], $data['desc'], $data['room_id']];
        update($query, $params, 'siiiiisi');
    
        // Delete existing relationships
        delete("DELETE FROM room_features WHERE room_id=?", [$data['room_id']], 'i');
        delete("DELETE FROM room_facilities WHERE room_id=?", [$data['room_id']], 'i');
        delete("DELETE FROM room_meal_plans WHERE room_id=?", [$data['room_id']], 'i');
    
        // Insert facilities
        $stmt = $this->conn->prepare("INSERT INTO room_facilities (room_id, facilities_id) VALUES (?, ?)");
        foreach ($facilities as $f) {
            $stmt->bind_param('ii', $data['room_id'], $f);
            $stmt->execute();
        }
        $stmt->close();
    
        // Insert features
        $stmt = $this->conn->prepare("INSERT INTO room_features (room_id, features_id) VALUES (?, ?)");
        foreach ($features as $f) {
            $stmt->bind_param('ii', $data['room_id'], $f);
            $stmt->execute();
        }
        $stmt->close();
        $stmt = $this->conn->prepare("INSERT INTO room_meal_plans (room_id, meal_plan_id, price_modifier) VALUES (?, ?, ?)");
        foreach ($meal_plans as $meal_plan_id => $price) {
            $mp_id = (int)$meal_plan_id;
            $mp_price = floatval($price);
            $stmt->bind_param('iid', $data['room_id'], $mp_id, $mp_price);
            $stmt->execute();
        }
        $stmt->close();

        return 1;
    }

    public function toggleStatus(int $roomId, int $value): int {
        return update("UPDATE rooms SET status=? WHERE id=?", [$value, $roomId], 'ii');
    }

    public function uploadImage($roomId, $file) {
        $img = uploadImage($file, $this->uploadDir);
        if (in_array($img, ['inv_img', 'inv_size', 'upd_failed'])) return $img;

        return insert("INSERT INTO room_images (room_id, image) VALUES (?, ?)", [$roomId, $img], 'is');
    }

    public function getRoomImages(int $roomId): string {
        $res = select("SELECT * FROM room_images WHERE room_id=?", [$roomId], 'i');
        $html = "";
        $path = ROOMS_IMG_PATH;

        while ($row = mysqli_fetch_assoc($res)) {
            $thumb = $row['thumb'] == 1
                ? "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>"
                : "<button onclick='thumb_image({$row['sr_no']},{$row['room_id']})' class='btn btn-secondary shadow-none'><i class='bi bi-check-lg'></i></button>";

            $html .= <<<HTML
                <tr class='align-middle'>
                  <td><img src='{$path}{$row['image']}' class='img-fluid'></td>
                  <td>{$thumb}</td>
                  <td>
                    <button onclick='rem_image({$row['sr_no']},{$row['room_id']})' class='btn btn-danger shadow-none'>
                      <i class='bi bi-trash'></i>
                    </button>
                  </td>
                </tr>
            HTML;
        }

        return $html;
    }

    public function removeImage($imageId, $roomId): int {
        $res = select("SELECT * FROM room_images WHERE sr_no=? AND room_id=?", [$imageId, $roomId], 'ii');
        $img = mysqli_fetch_assoc($res);

        if (deleteImage($img['image'], $this->uploadDir)) {
            return delete("DELETE FROM room_images WHERE sr_no=? AND room_id=?", [$imageId, $roomId], 'ii');
        }

        return 0;
    }

    public function setThumbnail($roomId, $imageId): int {
        update("UPDATE room_images SET thumb=? WHERE room_id=?", [0, $roomId], 'ii');
        return update("UPDATE room_images SET thumb=? WHERE sr_no=? AND room_id=?", [1, $imageId, $roomId], 'iii');
    }

    public function removeRoom(int $roomId): int {
        $res = select("SELECT * FROM room_images WHERE room_id=?", [$roomId], 'i');
        while ($r = mysqli_fetch_assoc($res)) {
            deleteImage($r['image'], $this->uploadDir);
        }

        delete("DELETE FROM room_images WHERE room_id=?", [$roomId], 'i');
        delete("DELETE FROM room_features WHERE room_id=?", [$roomId], 'i');
        delete("DELETE FROM room_facilities WHERE room_id=?", [$roomId], 'i');
        return update("UPDATE rooms SET removed=? WHERE id=?", [1, $roomId], 'ii');
    }

    
}
