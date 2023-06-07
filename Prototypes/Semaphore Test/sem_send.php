<?php   
    $id = 1234;
    $max = 1;
    $permissions = 0666;
    $autorelease = 1;

    $semaphore = sem_get($id, $max, $permissions, $autorelease);
    echo $semaphore;