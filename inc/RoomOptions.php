<?php

class RoomOptions
{
    private $conn;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    public function getRoomDetails(int $roomId): string
    {
        $room = mysqli_fetch_assoc(select("SELECT * FROM rooms WHERE id=?", [$roomId], 'i'));
        $meal_plans = mysqli_fetch_all(select("
        SELECT rmp.*, r.*, mp.name as name 
        FROM room_meal_plans as rmp , meal_plans mp, rooms r
        WHERE r.id = rmp.room_id AND rmp.room_id=? AND rmp.meal_plan_id = mp.id", [$roomId], 'i'), MYSQLI_ASSOC);
        return json_encode($meal_plans);
    }
}
