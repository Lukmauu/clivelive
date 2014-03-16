<?php
    require '_includes/func.php';
    
    destroySession();
    
    header("Location: index.php");
    exit;
?>