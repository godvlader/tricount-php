<?php
require_once "framework/Model.php";
class User extends Model
{
    private ?int $id;
    private string $mail;
    private string $hashed_password;
    private string $full_name;
    private string $role;
    private ?string $iban;

    protected const ROLE_ADMIN = 'admin';
    protected const ROLE_USER = 'user';

    protected const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER
    ];

    function __construct(?int $id, string $mail, string $hashed_password, string $full_name, ?string $role, ?string $iban)
    {
        $this->id = $id;
        $this->mail = $mail;
        $this->hashed_password = $hashed_password;
        $this->full_name = $full_name;
        $this->role = self::ROLE_USER;
        $this->iban = $iban;
    }

    public static function getUsers()
    {
        $result = [];
        $query = self::execute("SELECT * FROM  `users`", array());
        $data = $query->fetchAll();

        foreach ($data as $row) {
            $result[] = new User($row["id"], $row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"]);
        }
        return $result;

    }

    //retourne l'id de l'utilisateur
    function getUserId()
    {
        return $this->id;
    }

    function getFullName(): string
    {
        return $this->full_name;
    }

    function getUserIban(): string|null
    {
        return $this->iban;
    }

    function setUserIban(string $iban): void
    {
        $this->iban = $iban;
    }

    function getPassword(): string
    {
        return $this->hashed_password;
    }

    function setPassword(string $hashed_password): void
    {
        $this->hashed_password = $hashed_password;
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
        return $query;
    }

    public static function get_user_id_by_name($full_name)
    {
        $query = self::execute("SELECT id FROM  `users` where full_name=:fullname", array("fullname" => $full_name));
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $data;
        }
    }

    public static function get_by_name($full_name)
    { //récup l'user par son full_name
        $query = self::execute("SELECT * FROM  `users` where full_name=:fullname", array("fullname" => $full_name));
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return false;
        }
        return new User($data["id"], $data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);

    }

    function getIban(): string|null
    {
        return $this->iban;
    }

    function setFullName(string $fullname): string
    {
        return $this->full_name = $fullname;
    }


    function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    function update_password()
    {
        if (self::get_by_id($this->id) != null) {
            self::execute("UPDATE users SET
                hashed_password=:hashed_password WHERE id=:id ",
                array(
                    "hashed_password" => $this->hashed_password,
                    "id" => $this->id
                )
            );
        }
        return $this;
    }





    function setRole(string $role): void
    {
        $this->role = $role;
    }

    function getRole(): string
    {
        return $this->role;
    }

    function getMail(): string
    {
        return $this->mail;
    }
    function setMail(string $mail): string
    {
        return $this->mail = $mail;
    }

    function isAdmin(): string
    {
        return $this->role == "admin";
    }

    public static function get_by_id($id)
    { //récup l'user par son id
        $query = self::execute("SELECT * FROM  `users` where id=:id", array("id" => $id));
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return null;
        } else {
            return new User($data["id"], $data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }
    public static function get_all()
    { //récup tous les users
        $query = self::execute("SELECT * FROM  `users` ", array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User($row["id"], $row["mail"], $row["hashed_password"], $row["full_name"], $row["role"], $row["iban"]);
        }
        return $results;
    }
    function not_participate($tricountId)
    { //récup tous les users
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


    public static function get_by_mail($mail)
    { //récup l'user par son mail
        $query = self::execute("SELECT * FROM  `users` where mail=:mail", array("mail" => $mail));
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["id"], $data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }

    public static function get_user_by_name($full_name)
    { //récup l'user par son full_name
        $query = self::execute("SELECT * FROM  `users` where full_name=:fullname", array("fullname" => $full_name));
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["id"], $data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }

    public static function get_by_iban($iban)
    { //récup l'user par son iban
        $query = self::execute("SELECT * FROM  `users` where iban=:iban", array("iban" => $iban));
        $data = $query->fetch(); //un seul resultat max
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["id"], $data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["iban"]);
        }
    }

    function update_profile($full_name, $mail, $iban)
    {
        if (self::get_by_id($this->id) != null) {
            self::execute("UPDATE users
                    SET full_name=:full_name,
                    mail=:mail,
                    iban=:iban
                    where id=:id",
                array(
                    "id" => $this->id,
                    "full_name" => $full_name,
                    "mail" => $mail,
                    "iban" => $iban
                )
            );
        }
        return $this;
    }


    function update()
    {
        if (self::get_by_id($this->id) != null) {
            self::execute("UPDATE users SET
                mail=:mail,
                hashed_password=:hashed_password,
                full_name=:full_name,
                role=:role,
                iban=:iban,
                WHERE id=:id ",
                array(
                    "mail" => $this->mail,
                    "hashed_password" => $this->hashed_password,
                    "full_name" => $this->full_name,
                    "role" => $this->role,
                    "iban" => $this->iban,
                    "id" => $this->id
                )
            );
        } else {
            self::execute("INSERT INTO
                 `users`(mail,
                 hashed_password,
                 full_name,
                 role,
                 iban)
                VALUES(:mail,
                    :hashed_password,
                    :full_name,
                    :role,
                    :iban)",
                array(
                    "mail" => $this->mail,
                    "hashed_password" => $this->hashed_password,
                    "full_name" => $this->full_name,
                    "role" => $this->role,
                    "iban" => $this->iban
                )
            );
        }
        return $this;
    }

    //VALIDATIONS
    public static function validate_login($mail, $hashed_password): array
    {
        $errors = [];
        $user = User::get_by_mail($mail);
        if ($user) {
            if (!self::check_password($hashed_password, $user->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a user with the mail : '$mail'. Please sign up.";
        }
        return $errors;
    }

    public static function validate_login_it3($mail, $password)
    {
        // Retrieve the user with the provided email
        $user = User::get_by_mail($mail);
        // If a user exists with the given email
        if ($user !== null) {
            // Hash the provided password
            $hashed_password = Tools::my_hash($password);

            // Compare the hashed password with the stored hash
            return $hashed_password === $user->hashed_password;
        }

        // If no user exists with the given email, return false
        return false;
    }

    function validate(): array
    {
        $errors = [];
        if (isset($this->mail) && self::validateEmail($this->mail)) {
            $user = self::get_by_mail($this->mail);
            if (!is_null($user) && self::validate_unicity($this->mail)) {
                $errors[] = "This email is already used.";
            }

        }

        if (!(isset($this->full_name) && strlen($this->full_name) >= 3)) {
            $errors[] = "Full Name must be at least 3 characters.";
        }

        return $errors;
    }

    public static function validateFullName($full_name): bool
    {
        if (strlen($full_name) <= 3) {
            return false;
        }
        return true;
    }

    //for profile changes => the actual email won't get flagged as already in use
    function EmailExists($userId, $email) {
        $query = self::execute("SELECT mail FROM Users WHERE mail = :email AND id != :userId", array(":email" => $email, ":userId" => $userId));
        $data = $query->fetch();
        return $data ? true : false;
    }


    //for signup
    public static function EmailExistsAlready($email)
    {
        $query = self::execute("SELECT mail FROM Users WHERE mail=:email", array("email" => $email));
        $data = $query->fetch();
        return json_encode($data ? false : true);
    }


    public static function EmailCheckJSON($email)
    {
        $query = self::execute("SELECT mail FROM Users WHERE mail=:email", array("email" => $email));
        $data = $query->fetch(); //max one result
        $result = $data ? true : false;
        if ($result) {
            return json_encode(array("exists" => $result));
        } else {
            return json_encode(array("errorMessage" => "user not found"));
        }
    }



    public static function validateEmail($email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    function get_dette($operation): float
    {
        return Operation::get_dette_by_operation($operation, $this->id);
    }


    private static function validate_password($password)
    {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        }
        if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }

    public static function validate_passwords($password, $password_confirm)
    {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    public static function validate_unicity($email): array
    {
        $errors = [];
        $user = self::get_by_mail($email);
        if ($user) {
            $errors[] = "This email is already used.";
        }
        return $errors;
    }

    //indique si un mot de passe correspond à son hash
    public static function check_password(string $clear_password, string $hash): bool
    {
        return $hash === Tools::my_hash($clear_password);
    }

    function list_by_user()
    {
        $query = self::execute("SELECT * FROM `tricounts` t JOIN  subscriptions s ON t.id = s.tricount where user=:user", array("user" => $this->id));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Tricounts($data["ID"], $data["title "], $data["description"], $data["created_at"], $data["creator"]);
        }
    }

    function participates_in_tricount(): bool
    {
        $query = self::execute("SELECT *
                                FROM repartition_template_items
                                WHERE user = :user
                                LIMIT 1;
                                ", array("user" => $this->getUserId()));
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }

    }


    function can_be_delete($tricount): bool
    {
        $query = self::execute("SELECT count(*)
        FROM subscriptions
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
        );", array("tricount" => $tricount, "user" => $this->getUserId()));
        if ($query->fetchColumn() == 0) {
            return false;
        } else {
            return true;
        }
    }


    function deletable($tricount)
    {
        $query = self::execute("SELECT user
            FROM subscriptions
            WHERE tricount = :tricount
            AND user <> :creator
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
                WHERE operations.tricount = :tricount
            )
            AND user NOT IN (
                SELECT user
                FROM tricounts
                WHERE id = :tricount

            );", array("tricount" => $tricount, "creator" => $this->getUserId()));
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
    function beDeletable($tricount)
    {
        $query = self::execute("SELECT user
            FROM subscriptions
            WHERE tricount = :tricount
            AND user <> :creator
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
                WHERE operations.tricount = :tricount
            )
            AND user NOT IN (
                SELECT user
                FROM tricounts
                WHERE id = :tricount

            );", array("tricount" => $tricount, "creator" => $this->getUserId()));
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    function is_in_tricount($idTricount)
    {
        $query = self::execute("SELECT * from subscriptions s where s.user = :user and s.tricount =:id  ", array("user" => $this->id, "id" => $idTricount));
        if ($query->rowCount() == 0)
            return false;
        return true;
    }

    function is_in_tricount_by_template($idTemplate, $idTricount)
    {
        $query = self::execute("SELECT * FROM repartition_templates rt where rt.id =:id and rt.tricount =:tricount ", array("tricount" => $idTricount, "id" => $idTemplate));
        if ($query->rowCount() == 0)
            return false;
        return true;
    }
    function is_creator($idTricount)
    {
        $query = self::execute("SELECT * FROM tricounts t where t.creator =:user and t.id=:id ", array("user" => $this->id, "id" => $idTricount));
        if ($query->rowCount() == 0)
            return false;
        return true;
    }

    function is_in_items($idTemplate)
    {
        $query = self::execute("SELECT * FROM repartition_template_items rti where rti.user =:user and rti.repartition_template=:id ", array("user" => $this->id, "id" => $idTemplate));
        if ($query->rowCount() == 0)
            return false;
        return true;
    }


}
?>