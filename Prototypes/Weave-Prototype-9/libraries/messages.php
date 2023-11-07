<?php
    require_once "../model/dbInteract.php";

    class ChatInteractions {
        public static function createDMById($user1, $user2) {
            if(ChatInteractions::checkForDM($user1, $user2)){
                return;
            }
            $channelId = ChatInteractions::generateChannelId();
            ChatInteractions::createChannel(id: $channelId, participants: [$user1, $user2], channelType: 0);
            
            ChatInteractions::addDMToAccountById($channelId, $user1, $user2);
            ChatInteractions::addDMToAccountById($channelId, $user2, $user1);
        }

        public static function createChannel($channelType, $id = "", $hubId = "", $parentId = "", $participants = [], $channelName = "") {
            if($id === "") {
                $id = ChatInteractions::generateChannelId();
            }
            $participants = json_encode($participants);
            DBConnection::create(   "INSERT INTO channel_data (id, hubId, parentId, participants, channelType, channelName) VALUES (:id, :hubId, :parentId, :participants, :channelType, :channelName)", 
                                    [$id, $hubId, $parentId, $participants, $channelType, $channelName], 
                                    [":id", ":hubId", ":parentId", ":participants", ":channelType", ":channelName"]);
        }

        public static function generateChannelId() {
            do {
                $strId = strval(rand(0, PHP_INT_MAX));              //generate random numbers until the number is not in use
                $strId = str_pad($strId, 19, "0", STR_PAD_LEFT);                 //pad to 19 digits long
            } while (ChatInteractions::checkForChannelId($strId));

            return $strId;
        }

        public static function checkForChannelId($id) {
            $data = DBConnection::read("SELECT id FROM channel_data WHERE id = $id"); //get any matching IDs from database
            if (($data) == False) {     //check if there are any matching IDs
                return False;
            } else {
                return True;
            }
        }

        public static function addDMToAccountById($channelId, $accountId, $buddyId) {
            $data = DBConnection::read("SELECT dms FROM account_data WHERE id = :id", [$accountId], ["id"])[0];
            $dataDecoded = json_decode($data);
            array_push($dataDecoded, array("buddyId"=>$buddyId,"channelId"=>$channelId));
            $dataEncoded = json_encode($dataDecoded);
            DBConnection::update("UPDATE account_data SET dms = :dmData WHERE id = :id", [$dataEncoded, $accountId], [":dmData", ":id"]);
        }

        public static function deleteDMFromAccountByBuddyId($accountId, $buddyId) {
            $data = DBConnection::read("SELECT dms FROM account_data WHERE id = :id", [$accountId], [":id"]);
            $dataDecoded = json_decode($data[0], true);
            $returnData = [];
            foreach($dataDecoded as $dm) {
                if($dm["buddyId"] !== $buddyId) {
                    array_push($returnData, $dm);
                } 
            }
            $encodedReturnData = json_encode($returnData);
            DBConnection::update("UPDATE account_data SET dms = :dmData WHERE id = :id", [$encodedReturnData, $accountId], [":dmData", ":id"]);
        }

        public static function checkForDM($user1, $user2) {
            function checkAccount($accountId, $buddyId) {
                $data = DBConnection::read("SELECT dms FROM account_data WHERE id = :id", [$accountId], [":id"])[0];
                $dataDecoded = json_decode($data, true);    //"true" prevents arrays inside the main array from turning into stdClass objects
                foreach($dataDecoded as $dm) {
                    if ($dm["buddyId"] == $buddyId) {
                        return True;
                    }
                    return False;
                }
            }

            if(checkAccount($user1, $user2) || checkAccount($user2, $user1)) {
                return True;
            }

            return False;
        }

        public static function getDMsById($id) {
            $data = DBConnection::read("SELECT dms FROM account_data WHERE id = :id ", [$id], [":id"]);
            if($data == False) {return False;}
            return json_decode($data[0], true);
        }

        public static function getGroupChatsById($id) {
            $data = DBConnection::read("SELECT groupChats FROM account_data WHERE id = :id", [$id], [":id"]);
            if($data == False) {return False;}
            $channelIds = json_decode($data[0]);
            $returnData = [];
            foreach($channelIds as $channelId) {
                $channelData = DBConnection::read("SELECT participants, channelName FROM channel_data WHERE id = :channelId", [$channelId], [":channelId"]);
                if ($channelData !== false) {
                    array_push($returnData, array("channelId"=>$channelId, "channelName"=>$channelData["channelName"], "participants"=>$channelData["participants"]));
                }
            }
            //print_r($returnData);
            return $returnData;
        }

        // public static function getGroupChatNameById($id) {
        //     $data = DBConnection::read("SELECT channelName FROM channel_data WHERE id = :id", [$id], [":id"]);
        //     if ($data === False) {return False;};
        //     return $data[0];
        // }

        public static function getMessagesByChannelId($channelId, $startIndex, $endIndex) {
            $sql = "SELECT id, senderId, textContent, dateTimeSent 
            FROM messages
            WHERE channelId = '$channelId' 
            ORDER BY dateTimeSent DESC 
            LIMIT " . strval($endIndex - $startIndex + 1)
             . " OFFSET " . strval($startIndex - 1);
            
            //echo $sql;
            $data = array_reverse(DBConnection::readMany($sql));        //flipped so most recent messages come at the end
            
            return $data;
        }
    }