<?php

 function mlog($txtname,$data){
    $now = date("Y-m-d H:i:s",time());
    file_put_contents($txtname.".txt",var_export($now,1)."\r\n",FILE_APPEND);
    file_put_contents($txtname.".txt",var_export($data,1)."\r\n",FILE_APPEND);
    file_put_contents($txtname.".txt","================================"."\r\n",FILE_APPEND);
}