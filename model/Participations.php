<?php
require_once "framework/Model.php";

class Participations extends Model
{
    public int $tricount;
    public int $user;

    public function __construct($tricount, $user)
    {
        $this->tricount = $tricount;
        $this->user = $user;
    }

    public function get_tricount(): int
    {
        return $this->tricount;
    }

    public function get_user(): int
    {
        return $this->user;
    }


    function is_in_operation($operationId)
    {
        $query = self::execute(
            "SELECT user FROM repartitions WHERE operation = :id ",
            array("id" => $operationId)
        );
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    function get_dette($operation): int
    {
        if ($operation) {
            $dette = Operation::get_dette_by_operation($operation, $this->user);
            return (int) ($dette ?? 0); // return 0 if $dette is null
        } else {
            return 0;
        }
    }


    public static function get_by_tricount($tricount)
    {
        $query = self::execute("SELECT DISTINCT u.full_name, s.*, t.creator 
                                        from users u JOIN subscriptions s on s.user = u.id
                                        JOIN tricounts t on s.tricount = t.id
                                        where t.id =:tricount
                                        ORDER BY u.full_name ASC",
            array("tricount" => $tricount)
        );
        $participant = [];
        $data = $query->fetchAll();
        if ($query->rowCount() == 0)
            return null;
        foreach ($data as $row)
            $participant[] = new Participations($row["tricount"], $row["user"]);
        return $participant;
    }

    function getUserInfo()
    {
        $query = self::execute("SELECT u.full_name
                                    from `users` u, subscriptions s where u.id= s.user
                                    and s.user = :id", array("id" => $this->user));
        $data = $query->fetch();
        if ($query->rowCount() == 0)
            return null;
        return $data["full_name"];
    }

    public static function get_by_tricount_and_creator($tricount)
    {
        $query = self::execute("SELECT DISTINCT u.full_name
                        from subscriptions s, tricounts t, users u, repartition_template_items rti
                        where s.tricount =:tricount
                        and s.user = u.id
                        and u.id = rti.user
                        or u.id = t.creator;",
            array("tricount" => $tricount)
        );
        $data = $query->fetchAll();
        if ($query->rowCount() == 0)
            return null;
        return $data;
    }
    public static function get_by_user($user)
    {
        $query = self::execute(
            "SELECT * from subscriptions where user =:user",
            array("user" => $user)
        );
    }
    public static function delete_by_user_id_and_tricount($id, $tricount): bool
    {
        $query = self::execute("DELETE
                from subscriptions
                where user=:user
                And tricount=:tricount",
            array("user" => $id, "tricount" => $tricount)
        );
        if ($query->rowCount() == 0)
            return false;
        else
            return true;
    }


    public static function delete_by_user_id($id): bool
    {
        $query = self::execute("DELETE
                from subscriptions
                where user=:id",
            array("user" => $id)
        );
        if ($query->rowCount() == 0)
            return false;
        else
            return true;
    }
    public static function delete_by_tricount_id($id)
    {
        $query = self::execute("DELETE
                FROM subscriptions
                where tricount =:id",
            array("id" => $id)
        );
        if ($query->rowCount() == 0)
            return false;
        else
            return true;
    }
    function add()
    {
        self::execute(
            "INSERT INTO `subscriptions`(`tricount`, `user`) VALUES (:tricount,:user)",
            array("tricount" => $this->tricount, "user" => $this->user)
        );
    }
    function update()
    {
        if (self::get_by_tricount($this->tricount) != null) {
            self::execute("UPDATE subscriptions
                SET
                tricount=:tricount,
                user=:user
                where tricount=:tricount",
                array(
                    "tricount" => $this->tricount,
                    "user" => $this->user
                )
            );
        } else {
            self::execute("INSERT INTO
                subscriptions (tricount,
                user)
                VALUES(:tricount,
                :user)",
                array(
                    "tricount" => $this->tricount,
                    "user" => $this->user
                )
            );
        }
        return $this;
    }
    public static function by_tricount($tricount)
    {
        $query = self::execute("SELECT s.*
                                  FROM subscriptions s, tricounts t
                                  where s.tricount = t.id
                                  And s.tricount = :tricount
                                  ", array("tricount" => $tricount));
        $data = $query->fetchAll();
        $subscription = [];
        foreach ($data as $row) {
            $subscription[] = new Participations($row["tricount"], $row["user"]);
        }
        return $subscription;
    }

    function is_in_tricount($idTricount)
    {
        $query = self::execute("SELECT * from subscriptions s where s.user = :user and s.tricount =:id  ", array("user" => $this->user, "id" => $idTricount));
        $data = $query->fetch();
        if ($query->rowCount() == 0)
            return false;
        return $data;
    }
    function is_creator($idTricount)
    {
        $query = self::execute("SELECT * FROM tricounts t where t.creator =:user and t.id=:id ", array("user" => $this->user, "id" => $idTricount));
        $data = $query->fetch();
        if ($query->rowCount() == 0)
            return false;
        return $data;
    }
    function is_in_Items($templateID){
        $query = self::execute("SELECT DISTINCT rti.*
                from repartition_template_items rti, subscriptions o
                where o.tricount =:tricount
                and rti.repartition_template = :repartition_template
                and rti.user = :user",
            array(
                "tricount" => $this->tricount,
                "user" => $this->user,
                "repartition_template" => $templateID
            )
        );
        if ($query->rowCount() == 0) {
            return false;
        }
        return $query;

    }

    function is_user_in_items($templateID, $targetUserId)
    {
        $query = self::execute("SELECT DISTINCT rti.* 
            from repartition_template_items rti, subscriptions o 
            where o.tricount =:tricount
            and rti.repartition_template = :repartition_template 
            and rti.user = :user",
            array(
                "tricount" => $this->tricount,
                "user" => $this->user,
                "repartition_template" => $templateID
            )
        );

        while ($row = $query->fetch()) {
            if ($row['user'] == $targetUserId) {
                return true;
            }
        }

        return false;
    }

    function get_user_weight_in_items($templateID, $targetUserId)
    {
        $query = self::execute("SELECT DISTINCT rti.* 
            from repartition_template_items rti, subscriptions o 
            where o.tricount =:tricount
            and rti.repartition_template = :repartition_template 
            and rti.user = :user",
            array(
                "tricount" => $this->tricount,
                "user" => $this->user,
                "repartition_template" => $templateID
            )
        );

        while ($row = $query->fetch()) {
            if ($row['user'] == $targetUserId) {
                return $row['weight'];
            }
        }

        return null;
    }

    function get_weight_and_user_from_repartitions($operationId)
    {
        $query = self::execute("SELECT weight, user FROM repartitions 
    WHERE operation=:operationId ",
            array("operationId" => $operationId)
        );
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return 0;
        } else
            return ($data["weight"]);

    }


    function get_weight_by_user($repartition_template): int
    {
        $query = self::execute("SELECT weight FROM repartition_template_items 
                                WHERE repartition_template=:repartition_template 
                                AND user=:user",
            array("user" => $this->user, "repartition_template" => $repartition_template)
        );
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return 0;
        } else
            return ($data["weight"]);
    }

    function is_in_repartition($userId, $operationId)
    {
        $query = self::execute("SELECT user FROM repartitions where user =:user and operation=:operation ", array("user" => $this->get_user(), "operation" => $operationId));
        if ($query->rowCount() == 0)
            return false;
        return true;
    }

    function get_weight_by_user_and_operation($userId, $operationId)
    {
        $query = self::execute("SELECT weight FROM repartitions WHERE user=:user and operation=:operation", array("user" => $this->get_user(), "operation" => $operationId));
        $data = $query->fetch();
        if ($query->rowCount() == 0)
            return 0;
        return $data["weight"];
    }

    function get_weight_by_user_and_template($user, $templateId)
    {
        $query = self::execute("SELECT weight FROM repartition_template_items WHERE user=:user and repartition_template=:template", array("user" => $user, "template" => $templateId));
        $data = $query->fetch();
        if ($query->rowCount() == 0)
            return 0;
        return $data["weight"];
    }

}

?>