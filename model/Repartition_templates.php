<?php
require_once "framework/Model.php";

class Repartition_templates extends Model
{
  public $id = null;
  public $title; //(varchar 256)
  public $tricount;

  function __construct($id, $title, $tricount)
  {
    $this->id = $id;
    $this->title = $title;
    $this->tricount = $tricount;
  }

  function get_id(): ?int
  {
    return $this->id;
  }

  function get_title(): string
  {
    return $this->title;
  }
  function get_tricount(): int|null
  {
    return $this->tricount;
  }
  function setId($id)
  {
    $this->id = $id;
  }

  function setRepartitionTemplatesId()
  {
    $query = self::execute("SELECT id FROM repartition_templates WHERE id = :id", array("id" => Model::lastInsertId()));
    $data = $query->fetchAll();
    foreach ($data as $row) {
      $id = $row['id'];
    }
    $this->setId($id);
  }

  

  public static function get_by_id($id): Repartition_templates|null
  {
    $query = self::execute("SELECT * FROM  `repartition_templates` where id=:id", array("id" => $id));
    $data = $query->fetch(); //un seul resultat max
    if ($query->rowCount() == 0) {
      return null;
    } else {
      return new repartition_templates($data["id"], $data["title"], $data["tricount"]);
    }
  }
  public static function get_by_tricount($tricount)
  {
    $query = self::execute("SELECT * FROM `repartition_templates` where tricount=:tricount", array("tricount" => $tricount));
    $data = $query->fetchAll(); //c'était un fetch avant
    $templates = [];
    if ($query->rowCount() == 0) {
      return null;
    } else {
      foreach ($data as $row) {
        $templates[] = new Repartition_templates($row["id"], $row["title"], $row["tricount"]);
      }
      // return new repartition_templates($data["id"],$data["title"],$data["tricount"]);
      return $templates;
    }
  }


    function get_items(){
      $query =self::execute("select rti.* from repartition_template_items rti, repartition_templates rt, users u
                            where rt.id = rti.repartition_template 
                            and rt.id=:id 
                            and rti.user =u.id
                            ORDER BY u.full_name ASC", array("id"=>$this->id));
      $data = $query->fetchAll();
      $items=[];
      if ($query->rowCount() == 0){
        return null;
      } else{
        foreach($data as $row){
          $items[] = new repartition_template_items($row["weight"],$row["user"],$row["repartition_template"]);
          // dans items je dois renvoyer un user de la même façon
        }
      }
      return $items;
    }
    

    public static function title_already_exist_in_tricount($title,$tricountid) : bool{
      $query = self::execute("SELECT title FROM `repartition_templates` where title=:title and tricount =:id",array("title"=>$title,"id"=>$tricountid));
      if($query->rowCount() === 0 )
        return false;
      return true;
    } 
    public static function template_exist_in_tricount($id, $tricountid){
      $query = self::execute("SELECT * FROM  `repartition_templates` where id=:id AND tricount =:tricount", array("id" => $id, "tricount"=>$tricountid));
      $data = $query->fetch(); //un seul resultat max
      if ($query->rowCount() == 0) {
        return null;
      } else {
        return $data;
      }
    }
  

  public static function delete_by_tricount($tricount)
  {
    Repartition_template_items::delete_by_repartition_template($tricount);
    $query = self::execute("DELETE from `repartition_templates` where tricount=:tricount", array("tricount" => $tricount));
    if ($query->rowCount() == 0)
      return false;
    else
      return $query;
  }

  function delete_by_id()
  {
    //doit supprimer le tricount depuis repartition_template_items 
    Repartition_template_items::delete_by_repartition_template($this->id);
    $query = self::execute("DELETE from `repartition_templates` where id=:id", array("id" => $this->id));
    if ($query->rowCount() == 0)
      return false;
    return $query;
  }

  function newTemplate($titre, $tricount)
  {
    if (($titre === null || strlen($titre < 3)) || $tricount === null)
      return null;
    else {
      $query = self::execute("INSERT INTO
                                repartition_templates
                                (title, tricount)
                                VALUES(:titre,
                                      :tricount)",
        array(
          "titre" => $titre,
          "tricount" => $tricount
        )
      );

      $this->setRepartitionTemplatesId();
      return $query->fetch();
    }

  }


  function update_title($title)
  {
    self::execute("UPDATE `repartition_templates` SET
      title=:title,
      tricount=:tricount
      WHERE id=:id ",
      array(
        "id" => $this->id,
        "title" => $title,
        "tricount" => $this->tricount
      )
    );
    return $this;
  }

  function update()
  {
    if (!is_null($this->id)) {
      self::execute("UPDATE `repartition_templates` SET
          title=:title,
          tricount=:tricount
          WHERE id=:id ",
        array(
          "id" => $this->id,
          "title" => $this->title,
          "tricount" => $this->tricount
        )
      );
    } else {
      self::execute("INSERT INTO
          `repartition_templates` (title,tricount)
          VALUES(:title,
          :tricount)",
        array(
          "title" => $this->title,
          "tricount" => $this->tricount
        )
      );
    }
    return $this;
  }



  public static function validatetitle($title) :bool
  {
    $valid = false;
      if(strlen($title) >= 3 ){
          $valid = true;
      }
      return $valid;
  }

  public static function is_title_already_exist($title, $tricountid)
  {
    $query = self::execute("SELECT * FROM  `repartition_templates` where title=:title AND tricount =:tricount", array("title" => $title, "tricount" => $tricountid));
    if ($query->rowCount() == 0) {
      return false;
    } else {
      return true;
    }
  }

}




?>