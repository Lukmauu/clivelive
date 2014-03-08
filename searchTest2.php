<?php

/*
 * 
 * MAKE A STOP WORD LIST FOR PHP LIKE BEFORE GO TO SQL 
 * 
 */





if($_SERVER['REQUEST_METHOD'] !== "GET" || !isset($_GET['q'])){
    header("location: index.php");
}
function isLocal(){
    $_return = FALSE;
    if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
        $_return = TRUE; 
    }
    return $_return;
}
/*require '_includes/session.php';*/
require '_includes/clivelive-conn.php';
require '_includes/authentication.php';
require '_includes/func.php';

$get = sanitizePost($_GET);


$location = (isset($get['near']) && strlen($get['near']) > 1) ? 
                $get['near'] : NULL;

$tokens = preg_split('/\s+/', $get['q']);

array_map('trim', $tokens);
echoPre($tokens);

$_array_match   = array(
    'wa_id' => 0,
    'page_url' => 0,
    'page_content' => 0,
    'tags' => 0, 
    'extra' => 0,
    'relevance' => 0
);

$_array_like    = array(
    'wa_id' => 0,
    'page_url' => 0,
    'page_content' => 0,
    'tags' => 0, 
    'extra' => 0,
    'relevance' => 0
);

$like   = array();
$match  = "";
$where  = "";
$sel    = FALSE;
foreach($tokens as $token){
    if(strlen($token) >= 4){
        $match .= mysql_real_escape_string($token . " ");
    }else{
        array_push($like, mysql_real_escape_string("%" . $token . "%"));
    }
}
//echoPre($like);exit;

if(!empty($match)){
    $sel .= "SELECT *, MATCH (page_content) AGAINST ('{$match}' IN BOOLEAN MODE) 
        AS relevance FROM search WHERE MATCH (page_content) AGAINST ('{$match}' IN BOOLEAN MODE)
        ORDER BY relevance DESC";
}
$searchLike = FALSE;
if(count($like) >= 1){
    $count = 1;
    $searchLike =   "SELECT * FROM search WHERE ";

    foreach ($like as $key => $value) {
        if(count($like) > $count){
            $searchLike .= "page_content LIKE '{$value}' OR ";
        }else{
            $searchLike .= "page_content LIKE '{$value}'";
        }
        $count++;
    }
}
          
if($sel){                
    $preper = queryMysql($sel);
    while ($row = mysql_fetch_assoc($preper)) {

        if(count($row) > 1){

            array_push( $_array_match, $row );
        }else{
            echoPre('There id no results');
        }
    }
}

if($searchLike){
    $preper2 = queryMysql($searchLike);
    while($row2 = mysql_fetch_assoc($preper2)){
        if(count($row2) > 1){
            array_push($_array_like , $row2);
        }else{
            echoPre('There id no results form second query');
        }
    }
}
$_array_result = array();
if(count($_array_match) >= 1){
    if(count($_array_like) >= 1){
        $_array_result = array_merge($_array_match, $_array_like);
    }
}

foreach ($_array_result as $value) {
    if(!is_numeric($value)){
        json_encode($value);
    }
}




/*foreach($tokens as $token){
    if(strlen($token)>3)
        $squery .= "+$token* ";
    else
        $where .= " AND title LIKE '%$token%'";
if(!empty($squery))
    $select .= " , MATCH (title) AGAINST ('$squery' IN BOOLEAN MODE) AS score ";
}*/


    

?>


