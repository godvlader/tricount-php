<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/User.php';

class ControllerUser extends Controller
{

    //page d'accueil.
    public function index(): void
    {
        if (isset($_GET["param1"])) {
            $this->redirect('profile');
        }
    }

    public function logout(): void
    {
        Controller::logout();
    }

    public function profile()
    {
        $user = $this->get_user_or_redirect();
        $user = User::get_by_id($user->getUserId());
        (new View("profile"))->show(array("user" => $user)); //show may throw Exception
    }
    public function handle_can_be_delete_request()
    {
        // Get the JSON payload from the POST request
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        // Extract userId and tricountId from the JSON payload
        $userId = $input['userId'];
        $tricountId = $input['tricountId'];
        $creator = $input['creator'];
        var_dump($creator);
        // Pass the extracted data to the can_be_delete() function
        $deletable = $this->can_be_delete($userId, $tricountId, $creator);

        // Return the result as JSON
        echo json_encode([$userId => $deletable]);
    }

    public function can_be_delete($userId, $tricountId, $creator)
    {
        // Retrieve the tricount object
        //$tricount = Tricounts::get_by_id($tricountId);

        // Get the creator's user ID
        //$creatorUserId = $tricount->get_creator_id();

        // If the user is the creator, return false (not deletable)
        if ($userId == $creator) {
            return false;
        }

        // Otherwise, check if the user is deletable
        $user = User::get_by_id($userId);
        return $user->can_be_delete($tricountId);
    }

    public static function checkUserPass($password, $user)
    {

        $isOk = password_verify($password, $user->getPassword());

        return json_encode(['result' => $isOk]);
    }


    public function validateEmail($email)
    {
        $isValid = User::validateEmail($email);

        header('Content-Type: application/json');
        echo json_encode(['isValid' => $isValid]);
    }

    public function check_password_service()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $response = User::validate_login_it3($_POST['email'], $_POST['password']);
            header('Content-Type: application/json');
            echo json_encode(['success' => $response]);
        }
    }
    

    public function check_email_service()
    {
        if (isset($_POST['email'])) {
            $check = User::EmailExistsAlready($_POST['email']);
            echo $check;
        }
    }

    public function email_available()
    {

        if (isset($_POST['email'])) {
            $exis = User::EmailExistsAlready($_POST['email']);
            echo $exis;
        }
    }

    public function check_edit_prf_email() {
        if (isset($_POST['email'])) {
            $userId = User::get_by_mail($_POST['email']); // Fetch the userId from the session
            $emailExists = $userId->EmailExists($userId, $_POST['email']);
            echo $emailExists ? "true" : "false";
        }
    }

}