<?php

class EquipmentManager {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function addEquipment($data): int {
        $query = "INSERT INTO equipment (equipment_name, equipment_code, quantity, description)
                  VALUES (?, ?, ?, ?)";
        $params = [$data['equipment_name'], $data['equipment_code'], $data['quantity'], $data['desc']];

        return insert($query, $params, 'siis') ? 1 : 0;
    }

    public function getAllEquipment(): string {
        $res = select("SELECT * FROM equipment", [], '');
        $html = "";
        $i = 1;

        while ($row = mysqli_fetch_assoc($res)) {
            $html .= <<<HTML
                <tr class='align-middle'>
                    <td>{$i}</td>
                    <td>{$row['equipment_name']}</td>
                    <td>{$row['equipment_code']}</td>
                    <td>{$row['quantity']}</td>
                    <td>
                        <button type='button' onclick='edit_equipment({$row['id']})' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#edit-equipment'>
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button type='button' onclick='delete_equipment({$row['id']})' class='btn btn-sm btn-danger'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                    <td>{$row['description']}</td>
                </tr>
            HTML;
            $i++;
        }

        return $html;
    }

    public function getEquipmentDetails(int $equipmentId): string {
        $res = select("SELECT * FROM equipment WHERE id=?", [$equipmentId], 'i');
        $data = mysqli_fetch_assoc($res);
        return json_encode($data);
    }

    public function editEquipment($data): int {
        $query = "UPDATE equipment SET equipment_name=?, equipment_code=?, quantity=?, description=? WHERE id=?";
        $params = [$data['equipment_name'], $data['equipment_code'], $data['quantity'], $data['desc'], $data['equipment_id']];
        $result = update($query, $params, 'siisi');
        return $result !== false ? 1 : 0;
    }

    public function deleteEquipment(int $equipmentId): int {
        return delete("DELETE FROM equipment WHERE id=?", [$equipmentId], 'i');
    }
}
