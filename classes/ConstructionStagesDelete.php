<?php

class ConstructionStagesDelete
{
    public $name;
    public $startDate;
    public $endDate;
    public $duration;
    public $durationUnit;
    public $color;
    public $externalId;
    public $status;

    public function __construct($data)
    {
        if (is_array($data)) {
            $data = json_decode(json_encode($data), FALSE);
        }

        if (is_object($data)) {
            $vars = get_object_vars($this);

            foreach ($vars as $name => $value) {
                if (isset($data->$name)) {

                    $this->$name = $data->$name;
                }
            }
        }
    }

    public function setItem(int $id, mixed $db): array
    {
        $stmt = $db->prepare("
			SELECT
				status
			FROM construction_stages
			WHERE ID = :id
		");

        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if($result->status != "delete"){
            try {
                $stmt2 = $db->prepare("
                    UPDATE construction_stages
                    SET
                        status = :status
                    WHERE ID = :id
                ");

                $stmt2->execute([
                    'status' => 'delete',
                    'id' => $id,
                ]);

                return array(
                    'error' => false,
                    'message' => 'Item deleted successfully'
                );
            } catch (PDOException $e) {
                return array(
                    'error' => true,
                    'message' => $e->getMessage()
                );
            }
        }else{
            return array(
                'error' => true,
                'message' => 'Item already deleted'
            );
        }
        return array(
            "error" => true,
            "message" => "No item found"
        );
    }
}
