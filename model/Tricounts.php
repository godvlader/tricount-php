<?php

class Tricounts extends Model
{

  private $title; //(varchar 256)
  private $description; //(varchar 1024)
  private $created_at; //(datetime)
  private $creator; //(int)
  private $id; //(int)


  function __construct($id, $title, $description, $created_at, $creator)
  {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->created_at = $created_at;
    $this->creator = $creator;
  }
  //retourne l'id du tricount
  function get_id()
  {
    return $this->id;
  }
  //retourne le titre du tricount
  function get_title(): string
  {
    return $this->title;
  }
  //retourne la description
  function get_description()
  {
    return $this->description;
  }
  //retourne la date de création
  function get_created_at(): datetime
  {
    return $this->created_at;
  }

  //retourne l'id du créateur
  function get_creator_id(): int
  {
    return $this->creator;
  }

  public static function exists($id)
  {
    $query = self::execute("SELECT * FROM tricounts WHERE id = :id", array("id" => $id));
    $data = $query->fetch();
    if ($query->rowCount() == 0)
      return null;
    return $data;
  }

  public static function get_tricount_by_operation_id($id)
  {
    $query = self::execute("SELECT * FROM operations JOIN tricounts on operations.tricount = tricounts.id
    WHERE operations.id=:id", array("id" => $id));
    $data = $query->fetch();
    if ($query->rowCount() == 0) {
      return false;
    } else {
      return new Tricounts($data["id"], $data["title"], $data["description"], $data["created_at"], $data["creator"]);
    }
  }

  public static function get_tricount_by_user_id($id)
  {
    $query = self::execute("SELECT * FROM tricounts WHERE creator=:id", array("creator" => $id));
    $data = $query->fetchAll();
    $result = [];
    foreach ($data as $row) {
      $result[] = new Tricounts($row["id"], $row["title"], $row["description"], $row["created_at"], $row["creator"]);
    }
    return $result;
  }

  public static function get_my_total($id)
  {
    $query = self::execute("SELECT sum(amount) FROM operations WHERE initiator = :initiator", array("initiator" => $id));
    $data = $query->fetch();
    return $data;
  }

  public static function get_total_amount_by_tric_id($id)
  {
    $query = self::execute("SELECT sum(amount) FROM operations WHERE tricount = :tricount", array("tricount" => $id));
    $data = $query->fetch();
    return $data;
  }

  //retourne le tricount par son id
  public static function get_by_id($id)
  {
    $query = self::execute("SELECT * FROM tricounts WHERE id = :id", array("id" => $id));
    $data = $query->fetch();
    if ($query->rowCount() == 0) {
      return false;
    } else {
      return new Tricounts($data["id"], $data["title"], $data["description"], $data["created_at"], $data["creator"]);
    }
  }

  public static function get_by_title($title)
  {
    $query = self::execute("SELECT * FROM tricounts WHERE title = :title", array("title" => $title));
    $data = $query->fetch();
    if ($query->rowCount() == 0) {
      return false;
    } else {
      return new Tricounts($data["id"], $data["title"], $data["description"], $data["created_at"], $data["creator"]);
    }
  }
  private static function title_exist($title, $creator){
    $query = self::execute("SELECT title FROM tricounts WHERE title = :title and creator = :creator", array("title" => $title, "creator" => $creator));
    $data = $query->fetch();
    if ($query->rowCount() == 0) {
      return false;
    } else {
      return $data["title"];
    }
  }

  public static function validate_title($title, $creator)
  {
    $errors = [];
    $title = Tools::sanitize($title);
    if (!(isset($title) && is_string($title) && strlen($title) > 2)) {
      $errors[] = "Min. 3 characters for the Title is required";
    }
    if (!(isset($title) && is_string($title) && strlen($title) <= 256)) {
      $errors[] = "Title can only contain letters, spaces and dashes and a maximum length of 256";
    }
    if ($title === self::title_exist($title, $creator)){
      $errors[] = "This title already exist";
    }
    return $errors;
  }

  //retourne le tricount par son créateur
  public static function get_by_creator($creator)
  {
    $query = self::execute("SELECT * FROM tricounts WHERE creator = :creator", array("creator" => $creator));
    $data = $query->fetch();
    if ($query->rowCount() == 0) {
      return false;
    } else {
      return new Tricounts($data["id"], $data["title"], $data["description"], $data["created_at"], $data["creator"]);
    }
  }

  function update()
  {
    if (!is_null($this->id)) {
      self::execute(
        "UPDATE tricounts SET
          title=:title,
          description=:description,
          created_at=:created_at,
          creator=:creator
          WHERE id=:id ",
        array(
          "id" => $this->id,
          "title" => $this->title,
          "description" => $this->description,
          "created_at" => $this->created_at,
          "creator" => $this->creator,
        )
      );
    } else {
      self::execute(
        "INSERT INTO
          tricounts (id,title,description,
          created_at,
          creator)
          VALUES(:id,:title,
          :description,
          :created_at,
          :creator)",
        array(
          "id" => $this->id,
          "title" => $this->title,
          "description" => $this->description,
          "created_at" => $this->created_at,
          "creator" => $this->creator
        )
      );
    }
    return $this;
  }
  function addTricount()
  {
    self::execute(
      "INSERT INTO
          tricounts (title,description,
          created_at,
          creator)
          VALUES(:title,
          :description,
          :created_at,
          :creator)",
      array(
        "title" => $this->title,
        "description" => $this->description,
        "created_at" => $this->created_at,
        "creator" => $this->creator
      )
    );
    $this->id = self::lastInsertId();
  }

  function updateTricount($title, $description)
  {
    self::execute(
      "UPDATE tricounts set title=:title,description=:description where id=:id",
      array("id" => $this->id, "title" => $title, "description" => $description)
    );
  }
  function delete($id)
  {

    $query0 = self::execute("DELETE FROM repartitions WHERE operation IN (SELECT id FROM operations WHERE tricount = :id);", array("id" => $id));
    $query1 = self::execute("DELETE FROM repartition_template_items WHERE repartition_template IN (SELECT id FROM repartition_templates WHERE tricount=:id);", array("id" => $id));
    $query2 = self::execute("DELETE FROM repartition_templates WHERE tricount = :id;", array("id" => $id));
    $query3 = self::execute("DELETE FROM operations WHERE tricount = :id", array("id" => $id));
    $query4 = self::execute("DELETE FROM subscriptions WHERE tricount = :id;", array("id" => $id));
    $query5 = self::execute("DELETE from `tricounts` where id=:id", array("id" => $id));

    $data[] = $query0->fetchAll();
    $data[] = $query1->fetchAll();
    $data[] = $query2->fetchAll();
    $data[] = $query3->fetchAll();
    $data[] = $query4->fetchAll();
    $data[] = $query5->fetchAll();
    return $data;
  }
  public static function by_user($user)
  {
    $query = self::execute("SELECT t.title FROM `tricounts` t JOIN  subscriptions s ON t.id = s.tricount where user=:user", array("user" => $user));
    $data = $query->fetchAll();
    $tricount = [];
    foreach ($data as $row) {
      $tricount[] = new Tricounts($row["id"], $row["title"], $row["description"], $row["created_at"], $row["creator"]);
    }
    return $tricount;
  }
  public static function list($creator)
  {
    $query = self::execute(
      "SELECT DISTINCT tricounts.*
                              FROM tricounts
                              LEFT JOIN subscriptions
                              ON tricounts.id = subscriptions.tricount
                              WHERE tricounts.creator =:creator
                              OR subscriptions.user =:creator",
      array("creator" => $creator)
    );
    $data = $query->fetchAll();
    $tricount = [];
    foreach ($data as $row) {
      $tricount[] = new Tricounts($row["id"], $row["title"], $row["description"], $row["created_at"], $row["creator"]);
    }
    return $tricount;
  }
  public static function one_of_list()
  {
    $query = self::execute("SELECT * FROM `tricounts`", array());
    if ($query->rowCount() == 0) {
      return false;
    } else {
      $data = $query->fetch();
      return new Tricounts($data["id"], $data["title"], $data["description"], $data["created_at"], $data["creator"]);
    }
  }


  public static function number_of_friends($tricountId)
  { //recupère le nb d'amis sans le créateur du tricount
    $query = self::execute(
      "SELECT count(*)
                              FROM subscriptions s
                              where s.tricount =:tricountId
                              AND s.user NOT IN (SELECT creator FROM tricounts WHERE id = :tricountId)
                              ",
      array("tricountId" => $tricountId)
    );
    $data = $query->fetch();
    return $data[0];
  }

  function not_participate($tricountId) : array{ //récup tous les users
        $query = self::execute("SELECT *
            FROM users
            WHERE id
            NOT IN (SELECT user FROM subscriptions WHERE tricount =:tricountId)", array("tricountId" => $tricountId));
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User($row["id"], $row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"]);
        }
        return $results;
    }
  function not_participate_as_json($tricountId) : string{
        $users = $this->not_participate($tricountId);
        $table = [];
        foreach($users as $user){
          $row = [];
          $row["id"] = $user->getUserId();
          $row["mail"] = $user->getMail();
          $row["hashed_password"] = $user->getPassword();
          $row["full_name"] = $user->getFullName();
          $row["role"] = $user->getRole();
          $row["iban"] = $user->getUserIban();
          $table[] = $row;
        }
        return json_encode($table);
  }
  function subscribers($tricount){
    $query = self::execute("SELECT s.*
                            FROM subscriptions s, tricounts t
                            where s.tricount = t.id
                            And s.tricount = :tricount",
                            array("tricount"=>$tricount));
    $data = $query->fetchAll();
    $subscription  = array();
    $sub = array();

    foreach ($data as $row) {
      $subscription[] = User::get_by_id($row["user"]);
    }
    foreach($subscription as $s){
      $sub[] = $s;
    }

    return $sub;
  }
  function subscribers_as_json($tricountId) : string{
    $table = [];
    $users = $this->subscribers($tricountId);
    foreach($users as $user){
      $row = [];
      $row["id"] = $user->getUserId();
      $row["mail"] = $user->getMail();
      $row["hashed_password"] = $user->getPassword();
      $row["full_name"] = $user->getFullName();
      $row["role"] = $user->getRole();
      $row["iban"] = $user->getUserIban();
      $table[] = $row;
    }
    return json_encode($table);
  }
  function users_deletable($tricount, $userId): array {
    $query = self::execute(
      "SELECT *
      FROM subscriptions s
      WHERE tricount = :tricount
      AND user = :user
      AND user NOT IN (
        SELECT initiator
        FROM operations
        WHERE tricount = :tricount
      )
      AND user NOT IN (
        SELECT user
        FROM repartitions
        JOIN operations
        ON repartitions.operation = operations.id
        WHERE tricount = :tricount
      );",
      array("tricount" => $tricount, "user" => $userId)
    );
    $data = $query->fetchAll();
    $subscription = array();

    foreach ($data as $row) {
      $subscription[] = User::get_by_id($row["user"]);
    }

    return $subscription;
  }
  function users_deletable_as_json($tricountId,$userId) : string{
    $table = [];
    $users = $this->users_deletable($tricountId,$userId);
    foreach($users as $user){
      $row = [];
      $row["id"] = $user->getUserId();
      $row["mail"] = $user->getMail();
      $row["hashed_password"] = $user->getPassword();
      $row["full_name"] = $user->getFullName();
      $row["role"] = $user->getRole();
      $row["iban"] = $user->getUserIban();
      $table[] = $row;
    }
    return json_encode($table);
  }

  function get_expenses(): array|null
  {
    return Operation::get_operations_by_tricount($this->get_id());
  }

  function get_expenses_as_json()
  {
    $str = "";

    $expenses = $this->get_expenses();
    if (!empty($expenses)) {
      foreach ($expenses as $expense) {
        $expense_id = json_encode($expense->get_id());
        $title = json_encode($expense->getTitle());
        $tricount = json_encode($this->get_id());
        $amount = json_encode($expense->getAmount());
        $operation_date = json_encode($expense->getOperationDate());
        $initiator = json_encode($expense->getInitiator());
        $created_at = json_encode($expense->getCreatedAt());

        $str .= "{\"id\":$expense_id,\"title\":$title,\"tricount\":$tricount,\"amount\":$amount,\"operation_date\":$operation_date,\"initiator\":$initiator,\"created_at\":$created_at},";
      }
    }

    if ($str !== "")
      $str = substr($str, 0, strlen($str) - 1);
    return "[$str]";
  }

  public static function is_title_unique_for_creator($title, $creator)
  {
    $query = self::execute("SELECT COUNT(*) FROM `tricounts` WHERE title = :title AND creator = :creator", array("title" => $title, "creator" => $creator));
    $count = $query->fetchColumn();

    return $count == 0;
  }

}
