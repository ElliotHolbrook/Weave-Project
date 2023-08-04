<?php
    require_once "../model/dbInteract.php";

    //AccountInteractions will store the procedures and functions that involve communicating with the database to be used when do any kind of account management
    class AccountInteractions {
        //Used to get the hashed version of the password that is stored in the database
        public static function getPassHashedByEmail($email) {
            $data = DBConnection::read("SELECT passHashed FROM account_data WHERE email = :email", [$email], [":email"]);
            return $data[0];
        }

        public static function getUsernameByEmail($email) {
            $data = DBConnection::read("SELECT username FROM account_data WHERE email = :email", [$email], [":email"]);
            return $data[0];
        }

        public static function getIdByUsernameTag($username, $tag) {
            $data = DBConnection::read("SELECT id FROM account_data WHERE username = :username AND tag = :tag", [$username, $tag], ["username", "tag"]);
            return $data["id"];
        }

        public static function checkForEmail($email) {
            $data = DBConnection::read("SELECT id FROM account_data WHERE email = :email", [$email], [":email"]);
            if ($data == False) {
                return False;
            }
            return True;
        }

        public static function checkForUsernameTag($username, $tag) {
            $data = DBConnection::read("SELECT id FROM account_data WHERE username = :username AND tag = :tag", [$username, $tag], ["username", "tag"]);
            if ($data == False) {
                return False;
            }
            return True;
        }

        public static function addAccountToDatabase($account) {
            DBConnection::create("INSERT INTO account_data (username, email, passHashed, pin, id) VALUES (:username, :email, :passHashed, :pin, :id)", [$account->getUsername(), $account->getEmail(), $account->getPassHashed(), $account->getPin(), $account->getId()], [":username", ":email", ":passHashed", ":pin", ":id"]);
        }

        public static function addFriendToAccountById($accountId, $friendId) {
            $friends = DBConnection::read("SELECT friends FROM account_data WHERE id = :id", [$accountId], [":id"])[0];     //get json encoded frieds list
            $friendsDecoded = json_decode($friends);                //decode friends list
            array_push($friendsDecoded, intval($friendId));                 //add new friend
            $friendsEncoded = json_encode($friendsDecoded);         //encode friends list
            echo $friendsEncoded;
            DBConnection::update("UPDATE account_data SET friends = :friends WHERE id = :id", [$accountId, $friendsEncoded], [":id", ":friends"]);         //update stored accounts friends list
        }

        public static function checkForId($id) {
            $data = DBConnection::read("SELECT id FROM account_data WHERE id = $id"); //get any matching IDs from database
            if (($data) == False) {     //check if there are any matching IDs
                return False;
            } else {
                return True;
            }
        }

        public static function getAccountByEmail($email) {
            $data = DBConnection::read("SELECT username, tag, pin, id FROM account_data WHERE email=:email", [$email], [":email"]);
            $account = new Account(username: $data["username"],tag: $data["tag"], email: $email, pin: $data["pin"], id: $data["id"]);
            return $account;
        }

        public static function getAccountById($id) {
            $data = DBConnection::read("SELECT username, tag, email, pin FROM account_data WHERE id = $id");
            $account = new Account(username: $data["username"],tag: $data["tag"], email: $data["email"], pin: $data["pin"], id: $id);
            return $account;
        }

        public static function getFriendsById($id) {
            $data = DBConnection::read("SELECT friends FROM account_data WHERE id = $id");
            return json_decode($data[0]);
        }
    }

    //account functions is a class for functions that don't don't interract with the database but are required for validating inputted data and
    //preparing data for the database.
    class AccountFunctions {
        public static function validateUsername($username) {
            if (strlen($username) < 4) {
                return False;               //username length check
            }
            return True;
        }

        public static function validateEmail($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {   //email should be verified as well, not just validated
                return False;
            }
            return True;
        }
        
        public static function validatePassword($password) {
            //length check
            if ((strlen($password) < 7)) {          //check to make sure it is more than 6 characters long
                return false;
            }
            
            //upper case check
            if (!preg_match("/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/", $password)) {     //check to make sure it has upper case letters
                return false;
            }

            //lower case check
            if (!preg_match("/[abcdefghijklmnopqrstuvwxyz]/", $password)) {     //check to make sure it has lower case letters
                return false;
            }

            //symbol check
            if (!preg_match("/[£$%&*()}{@#~?>\'^<>,|=_+¬-]/", $password)) {     //check to make sure it has a symbol
                return false;
            }
            return true;
        }

        public static function validatePin($pin) {
            if (!is_numeric($pin)) {        //confirm that it is a number
                return false;
            } elseif (str_contains($pin, "e")) {    //confirm that there is no e or E
                return false;
            } elseif (str_contains($pin, "E")){
                return false;
            } elseif (strlen($pin) != 6) {      //confirm that it is the right length
                return false;
            }
            return true;
        }

        public static function hashPassword($password) {
            $passHashed = password_hash($password, PASSWORD_DEFAULT);   //use php password hashing function
            return $passHashed;
        }

        public static function generateId() {
            do {
                $strId = strval(rand(0, PHP_INT_MAX));              //generate random numbers until the number is not in use
                $strId = str_pad($strId, 19, "0", STR_PAD_LEFT);                 //pad to 19 digits long
            } while (AccountInteractions::checkForId($strId));

            return $strId;
        }

        public static function generateTagForUsername($username) {
            do {
                $tag = strval(rand(0, 99999));                              //generate tag
                $strId = str_pad($tag, 5, "0", STR_PAD_LEFT);                 //pad to 5 digits long
            } while (AccountInteractions::checkForUsernameTag($username, $tag));

            return $tag;
        }
    }

    class Account {
        private $username;
        private $tag;
        private $email;
        private $passHashed;
        private $pin;
        private $id;

        private $usernameSetResult;      //these hold the results of whether the last set attempt worked 
        private $tagSetresult;
        private $emailSetResult;
        private $passwordSetResult;
        private $pinSetResult;
        private $idSetResult;

        public function __toString() {
            return "Account object; username = $this->username; email = $this->email; id = $this->id; pin = $this->pin";
        }
        
        public function __construct($username = "", $tag = "", $email = "", $passHashed = "", $pin = "", $id = "") {        //sets all attributes when object created and defaults to ""
            $this->setUsername($username);
            $this->setTag($tag);
            $this->setEmail($email);
            $this->setPassHashed($passHashed);
            $this->setPin($pin);
            $this->setId($id);
        }

        private function setUsername($username) {
            if (AccountFunctions::validateUsername($username)) {          //use function to validate username
                $this->username = $username;
                $this->usernameSetResult = True;                   //setting result so it can be checked later to make sure it all works
            } else {
                $this->usernameSetResult = False;  
            }    
        }

        private function setTag($tag) {
            $this->tag = $tag;
        }

        private function setEmail($email) {
            if (AccountFunctions::validateEmail($email)) {          //email valdiation
                $this->email = $email;
                $this->emailSetResult = True;
            } else {
                $this->emailSetResult = False;
            }
        }

        private function setPassHashed($passHashed) {       //cannot validate if password has already been hashed so this should be done at hashing stage
            $this->passHashed = $passHashed;
            //$this->passwordSetResult = 
        }

        private function setPin($pin) {
            if (AccountFunctions::validatePin($pin)) {
                $this->pin = $pin;
                $this->pinSetResult =  True;
            }  else {
                $this->pinSetResult = False;
            }
        }

        private function setId($id) {
            $this->id = $id;
            //$this->idSetResult = 
        }

        public function getUsername() {
            return $this->username;
        }

        public function getTag() {
            return $this->tag;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPassHashed() {
            return $this->passHashed;
        }

        public function getPin() {
            return $this->pin;
        }

        public function getId() {
            return $this->id;
        }

        public function getUsernameSetResult() {
            return $this->usernameSetResult;
        }

        public function getTagSetResult() {
            return $this->tagSetResult;
        }

        public function getEmailSetResult() {
            return $this->emailSetResult;
        }

        public function getPasswordSetResult() {
            return $this->passwordSetResult;
        }

        public function getPinSetResult() {
            return $this->pinSetResult;
        }

        public function getIdSetResult() {
            return $this->idSetResult;
        }

    }
