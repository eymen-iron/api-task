<?php

class ConstructionStagesCreate
{
	public $name;
	public $startDate;
	public $endDate;
	public $duration;
	public $durationUnit;
	public $color;
	public $externalId;
	public $status;

	public function __construct($data) {

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
}