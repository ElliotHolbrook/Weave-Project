<?php  
    require('../libraries/MulticraftAPI.php');
    $api = new MulticraftAPI('https://panel.pebblehost.com/api.php', 'elliotbholbrook@gmail.com', 'Mr*Pps=o8S8HNJ');    //501962
    //print_r($api->getServerStatus(1, true)) . "<br><br>";

    $id = 567148;

    
    $stats = $api->getServerStatus($id, true)["data"];
    
    if ($stats == "") {
        echo "<span>Could not connect to minecraft server</span>";
        exit();
    }
    

    //print_r($api->getServerMetrics($id));
    if(($stats["status"]) == "online") {?>
        <span>Minecraft Server is </span><span style="color: green;">Online</span>
    <?php } else { ?>
        <span>Minecraft Server is </span><span style="color: red;">Offline</span>
    <?php } ?>

    <br>
    <span>Number of Online Players:  <?php echo $stats["onlinePlayers"] ?></span>
