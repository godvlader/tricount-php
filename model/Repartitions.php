<?php
require_once "model/User.php";
require_once 'framework/Model.php';
require_once 'model/Operation.php';

class Repartitions extends Model
{
    public ?int $weight;

    public ?int $operation;

    public ?int $user;

    function __construct(int $weight = NULL, int $operation, int $user)
    {
        $this->weight = $weight;
        $this->operation = $operation;
        $this->user = $user;
    }

    function getWeight()
    {
        return $this->weight;
    }

    function getOperation()
    {
        return $this->operation;
    }

    function getUser()
    {
        return $this->user;
    }

    // public static function get_user_and_weight_by_operation_id($operation){
    //     $query = self::execute("SELECT user, weight FROM repartitions WHERE operation=:id", array("id"=>$operation));
    //     $data = $query->fetchAll();
    //     return $data;
    // }

    public static function get_by_name($templateName)
    {
        $query = self::execute("SELECT weight, operation, user FROM repartitions WHERE operation=:id
        ", array("id" => $templateName));
        $repartitions = array();
        while ($data = $query->fetch()) {
            if ($data !== NULL) {
                $repartition = new Repartitions($data["weight"], $data["operation"], $data["user"]);
                $repartitions[] = $repartition;
            }
        }

        return $repartitions;
    }


    public static function get_by_operation($operationId)
    {
        $query = self::execute("SELECT weight, operation, user FROM repartitions WHERE operation=:id
        ", array("id" => $operationId));
        $repartitions = array();
        while ($data = $query->fetch()) {
            if ($data !== NULL) {
                $repartition = new Repartitions($data["weight"], $data["operation"], $data["user"]);
                $repartitions[] = $repartition;
            }
        }

        return $repartitions;
    }

    public static function update($operationId, $checkedUsers, $weights)
    {
        try {
            if (is_string($checkedUsers)) {
                $checkedUsers = array($checkedUsers);
            }
            for ($i = 0; $i < count($checkedUsers); $i++) {
                $userId = $checkedUsers[$i];
                $weight = $weights[$i];

                $query = self::execute(
                    "UPDATE repartitions SET weight = :weight WHERE operation_id = :operation_id AND user_id = :user_id",
                    array(
                        "operation_id" => $operationId,
                        "user_id" => $userId,
                        "weight" => $weight
                    )
                );
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }   

}

?>