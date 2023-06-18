<?php

class ConstructionStagesUpdate
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
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE ID = :id
		");

        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $messageVals = array();
        $messages = array();
        if ($result) {
            foreach ($result as $key => $value) {
                if (empty($this->$key)) {
                    $this->$key = $value;
                }else{
                    if($key != 'endDate'){
                     $messageVals[] = getValidate($key, $this->$key);
                     $this->duration = calculateDuration($this->startDate, $this->endDate, $this->durationUnit);
                    }else{
                        $messageVals[] = getValidate($key, $this->$key, $this->startDate);
                        $this->duration = calculateDuration($this->startDate, $this->endDate, $this->durationUnit);
                    }
                }
            }
            foreach($messageVals as $key){
                foreach($key as $k => $v){
                    if($v['error']){
                        $messages[] = $v;
                    }
                }
            }

            if(!empty($messages)){
                return $messages;
            }

            try {
                $stmt2 = $db->prepare("
                    UPDATE construction_stages
                    SET
                        name = :name,
                        start_date = :start_date,
                        end_date = :end_date,
                        duration = :duration,
                        durationUnit = :durationUnit,
                        color = :color,
                        externalId = :externalId,
                        status = :status
                    WHERE ID = :id
                ");

                $stmt2->execute([
                    'name' => $this->name,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                    'duration' => $this->duration,
                    'durationUnit' => $this->durationUnit,
                    'color' => $this->color,
                    'externalId' => $this->externalId,
                    'status' => $this->status,
                    'id' => $id,
                ]);

                return array(
                    'error' => false,
                    'message' => 'Item updated successfully'
                );
            } catch (PDOException $e) {
                return array(
                    'error' => true,
                    'message' => $e->getMessage()
                );
            }
        }

        return array(
            "error" => true,
            "message" => "No item found"
        );
    }
}
