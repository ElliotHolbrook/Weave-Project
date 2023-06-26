<?php
    require_once "dbInteract.php";

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

        public static function addAccountToDatabase($account) {
            DBConnection::create("INSERT INTO account_data (username, email, passHashed, pin, id) VALUES (:username, :email, :passHashed, :pin, :id)", [$account->getUsername(), $account->getEmail(), $account->getPassword(), $account->getPin(), $account->getId()], [":username", ":email", ":passHashed", ":pin", ":id"]);
        }

        public static function checkForId($id) {
            $data = DBConnection::read("SELECT id FROM account_data WHERE id = " . $id); //get any matching IDs from database
            if (($data) == False) {     //check if there are any matching IDs
                return False;
            } else {
                return True;
            }
        }
    }

    //account interactions is a class for functions that don't don't interract with the database but are required for validating inputted data and
    //preparing data for the database.
    class AccountFunctions {
        public static function validatePassword($username) {
            //length check
            if ((strlen($username) < 7)) {          //check to make sure it is more than 6 characters long
                return false;
            }
            
            //upper case check
            if (!preg_match("/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/", $username)) {     //check to make sure it has upper case letters
                return false;
            }

            //lower case check
            if (!preg_match("/[abcdefghijklmnopqrstuvwxyz]/", $username)) {     //check to make sure it has lower case letters
                return false;
            }

            //symbol check
            if (!preg_match("/[£$%&*()}{@#~?>\'^<>,|=_+¬-]/", $username)) {     //check to make sure it has a symbol
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
            } while (AccountInteractions::checkForId($strId));

            $strId = str_pad($strId, 19, "0", STR_PAD_LEFT);                 //pad to 19 digits long
            return $strId;
        }
    }

    class Account {
        private $username;
        private $email;
        private $password;
        private $pin;
        private $id;

        public function __construct($username, $email, $password, $pin, $id) {
            $this->setUsername($username);
            $this->setEmail($email);
            $this->setPassword($password);
            $this->setPin($pin);
            $this->setId($id);
        }

        private function setUsername($username) {
            $this->username = $username;
        }

        private function setEmail($email) {
            $this->email = $email;
        }

        private function setPassword($password) {
            $this->password = AccountFunctions::hashPassword($password);
        }

        private function setPin($pin) {
            $this->pin = $pin;
        }

        private function setId($id) {
            $this->id = $id;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getPin() {
            return $this->pin;
        }

        public function getId() {
            return $this->id;
        }
    }
