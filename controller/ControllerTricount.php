<?php
require_once 'model/Repartition_templates.php';
require_once 'model/Repartition_template_items.php';
require_once 'model/User.php';
require_once 'model/Operation.php';
require_once 'model/Tricounts.php';
require_once 'model/Participations.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTricount extends Controller{
  public function index() :void{
    $this->tricount_list();
  }

  public function get_title_service() {
    if (isset($_POST['title']) && isset($_POST['creator'])) {
        $title = $_POST['title'];
        $creator = $_POST['creator'];
        $isTitleUnique = Tricounts::is_title_unique_for_creator($title, $creator);
        //JSON response
        $response = array('unique' => $isTitleUnique);

        header('Content-Type: application/json');
        echo json_encode($response);
    }
  }


  public function tricount_list(){
    $loggedUser = $this->get_user_or_redirect();
    if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
      $this->redirect('main', "error");
    }
    $user= array_key_exists('param1', $_GET) && $loggedUser->isAdmin() ? User::get_by_id($_GET['param1']) : $loggedUser;
    if (is_null($user)) {
      $user = $loggedUser;
    }
    $tricounts_list = Tricounts::list($user->getUserId());
    (new View("list_tricounts"))->show(array("loggedUser" => $loggedUser, "user" => $user, "tricounts_list"=>$tricounts_list));
  }

  public function add(){
    $user = $this->get_user_or_redirect();
    if (!is_null($user)) {
      $id = null;
      $errors = [];
      $title = '';
      $description = '';
      $tricount = '';
      $created_at = date('Y-m-d H:i:s');
      if ( (isset($_POST["title"]) && $_POST["title"]!="")&&(isset($_POST["description"])|| $_POST["description"]=="")){
        $title = Tools::sanitize($_POST["title"]);
        $errors = Tricounts::validate_title($title, $user->getUserId());
        $description = Tools::sanitize($_POST["description"]);
        $creator = $user->getUserId();
        $tricount = new Tricounts($id, $title, $description, $created_at, $creator);
        $tricountBool = Tricounts::get_by_title($tricount->get_title());
        if(strlen($description) <3)
          $errors[] = "description must be 3 characters long minimum";
        if($tricountBool == true){
          $errors[]  = "This tricount already exist";
        }
        if (count($errors) == 0) {
          $tricount->addTricount();
          $idT = $tricount->get_id();
          $newSubscriber = new Participations($idT, $tricount->get_creator_id());
          $newSubscriber->add();
          $this->redirect("tricount", "result", $idT);
        }
      }
      (new View("add_tricount"))->show(array("user" => $user,"tricount" =>$tricount, "errors"=>$errors));
    } else {
      $this->redirect("user","profile");
    }
  }
  public function result() {
    if (!empty($_GET["param1"])) {
      $user = $this->get_user_or_redirect();
        // load tricount corresponding to param
        $id = $_GET["param1"];
        $tricount = Tricounts::get_by_id($id);
        // display results with last created tricount
        $this->redirect("tricount","index");
    }
  }

  public function edit(){
    $user = $this->get_user_or_redirect();
    $id = null;
    $sub = [];
    $users_deletable = "";
    $users_deletable = "";
    $errors = [];
    if (isset($_GET['param1']) || isset($_POST['param1'])) {
      $id = isset($_POST['param1']) ? $_POST['param1'] : $_GET['param1'];
      $backValue = "operation/expenses/". $id;
      $tricountExist = Tricounts::exists($id);
      if(empty($tricountExist)){
        $this->redirect('tricount', "index");
      }
      $tricount = Tricounts::get_by_id($id);
      $subscriptions = $tricount->subscribers($tricount->get_id());
      $subscribers_json = $tricount->subscribers_as_json($tricount->get_id());
      $users = $tricount->not_participate($tricount->get_id());
      $users_json = $tricount->not_participate_as_json($tricount->get_id());
      $users_deletable = [];
      foreach($subscriptions as $s){
        $sub[] = User::get_by_id($s->getUserId());
      }
      foreach($subscriptions as $s){
        $user = User::get_by_id($s->getUserId());
        if($user->getUserId() !== $tricount->get_creator_id()) {
          $users_deletable[$user->getUserId()] = $s->can_be_delete($tricount->get_id());
        }
      }
    }else {
      $this->redirect("tricount","index");
    }
    (new View("edit_tricount"))->show(array("user" => $user,"tricount" => $tricount,"subscriptions" =>$subscriptions, "sub" => $sub,"users" => $users,"users_json"=>$users_json,"subscribers_json"=>$subscribers_json,"users_deletable"=>$users_deletable,"errors"=>$errors,
                  "backValue"=>$backValue));
  }

  public function get_visible_users_service() : void {

    if(isset($_GET["param1"]) && $_GET["param1"]!=""){
        var_dump($_GET["param1"]);
        $id = $_GET['param1'];
        $tricount = Tricounts::get_by_id($id);
        $users_json = $tricount->not_participate_as_json($id);
    }
    echo $users_json;
  }

  public function delete(){
    $user = $this->get_user_or_redirect();
    if (isset($_GET['param1']) && is_numeric($_GET['param1']) && $_GET['param1'] != null ) {
      $tricountExist = Tricounts::exists($_GET['param1']);
      if(empty($tricountExist)){
        $this->redirect('tricount', "index");
      }
      $id = $_GET['param1'];
      $tricount = Tricounts::get_by_id($id);
      if($tricount->get_creator_id() === $user->getUserId()){
        (new View("delete_tricount"))->show(array("user" => $user,"tricount" => $tricount));
      }else {
        $this->redirect('main', "error");
      }
    }
  }
  public function delete_confirm(){
    $user = $this->get_user_or_redirect();
    if (isset($_GET['param1']) && is_numeric($_GET['param1']) && $_GET['param1'] != null ) {
      $id = $_GET['param1'];
      $tricount = Tricounts::get_by_id($id);
      if($tricount->get_creator_id() === $user->getUserId()){
        $tricount->delete($tricount->get_id());
        $this->redirect('tricount', "index");
      }else {
        $this->redirect('main', "error");
      }
    }
  }

  public function update(){
    $user = $this->get_user_or_redirect();
    $errors = [];
    if (!is_null($user)) {
      if (isset($_GET['param1']) && is_numeric($_GET['param1']) && $_GET['param1'] != null
          && isset($_POST["title"]) && !empty($_POST["title"])
          && isset($_POST["description"])|| ($_POST["description"]=="")){
        $user_id = $user->getUserId();

        $id = $_GET['param1'];
        $title = Tools::sanitize($_POST["title"]);
        $tricount = Tricounts::get_by_id($id);

        /**
         *  sans le ucfirst(strtolower($title)) on recoit l'erreur de constraint.
         */
        if($tricount->get_title() !== $title)
          $errors = Tricounts::validate_title(ucfirst(strtolower($title)) , $user_id);
        // var_dump($tricount->get_title(). " ---- ". $title);
        // foreach($errors as $e)
        //     var_dump($e);
        // die();
        $description = Tools::sanitize($_POST["description"]);
        if(strlen($description) <3)
            $errors[] = "Description must be 3 characters minimum";
        $subscriptions = Participations::by_tricount($tricount->get_id());
        $users = $tricount->not_participate($tricount->get_id());
        foreach($subscriptions as $s){
          $sub[] = User::get_by_id($s->user);
        }
        if (count($errors) == 0) {
          $tricount->updateTricount($title,$description);
          $idT = $tricount->get_id();
          $this->redirect("tricount", "result", $idT);
        }
        else {
          // Handle error for invalid tricount id
          (new View("edit_tricount"))->show(array("user" => $user,"tricount" => $tricount,"subscriptions" =>$subscriptions, "sub" => $sub,"users" => $users, "errors"=>$errors));
        }
      }
    } else {
      $this->redirect("user","profile");
    }
  }

  public function delete_service(){
    if(isset($_GET['param1']) && $_GET['param1'] !== ""){
        $tricount = Tricounts::get_by_id($_GET['param1']);
        $tricount = $tricount->delete($tricount->get_id());
    }
  }

  public function check_title(){
      $title = $_POST['title'];
      $tricount_id = $_POST['tricId'];
      $originalTricount = Tricounts::get_by_id($tricount_id);

      if($originalTricount === false || $originalTricount === null){
          echo json_encode(['isUnique' => false]);
          return;
      }
      $tricountByTitle = Tricounts::get_by_title($title);

      if ($tricountByTitle !== null && $tricountByTitle->get_id() != $originalTricount->get_id()) {
          echo json_encode(['isUnique' => false]);
      } else {
          echo json_encode(['isUnique' => true]);
      }
  }

}

?>
