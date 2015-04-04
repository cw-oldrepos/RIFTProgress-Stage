<?php
	include_once 	"properties.php";

	//***PASSWORD RESET***//
	//echo "Password: trinity Encrypt: ".(encryptPassword("Raive", "trinity"))."<br>";
	echo "RUNNING ***UPDATE_SHARD_FIRSTS***<br>";
	/*$encryptPassword = sha1($pass);
	
	for ( $count = 0; $count < 5; $count++ ) {
		$encryptPassword = sha1($encryptPassword.$count);
	}
	
	crypt($encryptPassword);*/

    $encrypt_password = sha1("RiftProgress8934752231");
            
    for ( $count = 0; $count < 5; $count++ ) {
        $encrypt_password = sha1($encrypt_password.$count);
    }        

    crypt($encrypt_password);

    echo $encrypt_password;	
?>