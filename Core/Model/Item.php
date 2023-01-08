<?php

namespace Core\Model;

use Core\Base\Model;

class Item extends Model
{
    /**
     * update the available quantity on making transaction
     * @param mixed $data
     * @return void
     */
    public function update_available_quantity($data)
    {
        $id = $data['id'];
        $available_quantity = $data["available_quantity"];

        $sql = "UPDATE $this->table SET available_quantity=? WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $available_quantity, $id);
        $stmt->execute();
        $stmt->close();
    }
    /**
     * Get top 5 expensive items in items table
     * @return array
     */
    public function get_top_prices()
    {
        $data = array();
        $sql = "SELECT items.name,items.price FROM $this->table ORDER BY price DESC LIMIT 5";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
        }
        $stmt->close();
        return $data;
    }
}
