<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';


class ControllerProfile extends Controller
{

    // static int $first_time = 0;

    public function index(): void
    {
        $this->profile();
    }

    //profil de l'utilisateur connecté ou donné

    /**
     * @throws Exception
     */
    public function profile()
    {
        $loggedUser = $this->get_user_or_redirect();
        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        // $user= array_key_exists('param1', $_GET) && $user->isAdmin() ?
        //     User::get_by_id($_GET['param1']) : $user;
        if (isset($_GET['param1']) && is_numeric($_GET['param1'])) {
            if($_GET['param1'] === $loggedUser->getUserId())
                $loggedUser = User::get_by_id($loggedUser->getUserId());
        }
        (new View("profile"))->show(array("user"=>$loggedUser)); //show may throw Exception
    }

    public function change_password()
    {
        $user = $this->get_user_or_redirect();
        $user = User::get_by_id($user->getUserId());

        $errors = [];
        $backValue= "user/profile/". $user->getUserId();

        $success = array_key_exists('param2', $_GET) && $_GET['param2'] === 'ok' ? 
            "Your password has been successfully changed." : '';

        // If the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate the entered passwords
            if (
                array_key_exists('newPassword', $_POST)
                && array_key_exists('confirmPassword', $_POST)
            ) {
                $newPass = Tools::sanitize($_POST["newPassword"]);
                $confirmPass = Tools::sanitize($_POST["confirmPassword"]);
                $errors = User::validate_passwords($newPass, $confirmPass);

                // If the connected user is updated, also verify the current password
                if (array_key_exists('currentPassword', $_POST)) {
                    $currPass = Tools::sanitize($_POST["currentPassword"]);
                    if (!User::check_password($currPass, $user->getPassword())) {
                        $errors[] = "The current password is not correct.";
                    }
                }
                // If passwords are valid, update user
                if (empty($errors)) {
                    $user->setPassword(Tools::my_hash($newPass));
                    $user->update_password();
                    $this->redirect("user", "profile", $user->getUserId(), "ok");
                }
            }
        }

        // Get the submitted values if they exist
        $newPasswordValue = array_key_exists('newPassword', $_POST) ? $_POST['newPassword'] : '';
        $confirmPasswordValue = array_key_exists('confirmPassword', $_POST) ? $_POST['confirmPassword'] : '';
        $currentPasswordValue = array_key_exists('currentPassword', $_POST) ? $_POST['currentPassword'] : '';

        (new View("change_password"))->show([
            "user" => $user,
            "errors" => $errors,
            "success" => $success,
            "newPasswordValue" => $newPasswordValue,
            "confirmPasswordValue" => $confirmPasswordValue,
            "currentPasswordValue" => $currentPasswordValue,
            "backValue" => $backValue
        ]);
    }


    public function edit_profile()
   {
       /** @var User $user */
        $loggedUser = $this->get_user_or_redirect();
        $errors = [];


        $user = User::get_by_id($loggedUser->getUserId());
        $success = array_key_exists('param2', $_GET) && $_GET['param2'] === 'ok' ?
            "Your profile has been successfully updated." : "";

        $backValue= "user/profile/". $user->getUserId();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST["mail"]) || isset($_POST["fullName"]) || isset($_POST["iban"])) {
                if (isset($_POST["mail"])) {
                    $mail = Tools::sanitize($_POST["mail"]);
                    $fullname = Tools::sanitize($_POST["fullName"]);
                    $iban = Tools::sanitize($_POST["iban"]);
                    if (!User::validateEmail( $mail)) {
                        $errors[] = "Wrong mail";
                    }
                    if ($loggedUser->EmailExists($loggedUser->getUserId(), $_POST['mail'])) {
                        $errors[] = "Email address is already in use.";
                    }

                }
                if (isset($_POST["fullName"])) {
                    if (!User::validateFullName( $fullname)) {
                        $errors[] = "Bad name. Too short.";
                    }
                }
            }
            if(empty($errors)){
                $user->update_profile($fullname, $mail, $iban);
                $this->redirect("user","profile",$user->getUserId(),"ok");
            }
        }

        $mailValue = array_key_exists('mail', $_POST) ? $_POST['mail'] : '';
        $fullnameValue = array_key_exists('fullname', $_POST) ? $_POST['fullname'] : '';
        $ibanValue = array_key_exists('iban', $_POST) ? $_POST['iban'] : '';
        (new View("edit_profile"))->show([
            "user" => $user,
            "errors" => $errors,
            "success" => $success,
            "mailValue" => $mailValue,
            "fullnameValue" => $fullnameValue,
            "ibanvalue" => $ibanValue,
            "backValue" => $backValue
        ]);
    }
    public function result_profile()
    {
        // $user = $this->get_user_or_redirect();
        // $user = array_key_exists('param1', $_GET) && $user->isAdmin() ?
        //    User::get_by_id($_GET['param1']) : $user;
        $user = $this->get_user_or_redirect();
        if(!empty($_GET["param1"])){//récup l'id du user
            if($user->getUserId() == $_GET["param1"]){
                $user = User::get_by_id($_GET["param1"]);
            } else {
                $this->redirect("main", "error");
            }
            (new View("profile"))->show(array("user" => $user));
        }
    }

}
