<?php
if($_SERVER['REQUEST_METHOD'] != "GET"){
    header("location: index.php");
}
function isLocal(){$_return=FALSE;if($_SERVER['SERVER_NAME'] == '127.0.0.1'){$_return=TRUE;}return $_return;}
require '_includes/clivelive-conn.php';
require '_includes/authentication.php';
require '_includes/func.php';

$_ARRAY_RESULTS = array();
$return = "";
$tokens = "";
$_temp1 = "";
$_temp2 = "";
$get = sanitizePost($_GET);

$getQ       = ( strlen($get['q']) > 2)  ? $get['q'] : NULL;
$getNear    = (strlen($get['near']) > 2) ? $get['near'] : NULL;
if(is_null($getQ) && is_null($getNear)){
    echo "FALSE"; exit;
}else if(!is_null($getQ) && !is_null($getNear)){
    $_temp1 = preg_split('/\s+/', $getNear);
    $_temp2 = preg_split('/\s+/', $getQ);
    $tokens = array_merge($_temp1, $_temp2);
}else if(!is_null($getNear)){
    $tokens = preg_split('/\s+/', $getNear);
}else{
    $tokens = preg_split('/\s+/', $getQ);
}
/* TRIM TOKENS */
array_map('trim', $tokens);
/* CLEAN NULL */
foreach ($tokens as $key => $value) {
    if(is_null($value)){
        unset($tokens[$key]);
    }else if($value == " " || $value == "" || $value == "  "){
        unset($tokens[$key]);
    }
}

$_array_match   = array();
$_array_like    = array();
$like           = array();
$match          = "";
$match2         = "";
$matchQ         = FALSE;
$matchQ2        = FALSE;
$likeQ          = FALSE;

foreach($tokens as $token){
    
    if(strlen($token) >= 4){
        $match .= sanitizeString("+" . $token . ", ");
        $match2 .= sanitizeString($token . ", ");
    }else{
        array_push($like, sanitizeString("%" . $token . "%"));
    }
}

if(strlen($match) > 1){
    $match = substr($match, 0, -2);
    $match2 = substr($match2, 0, -2);
    $matchQ = "SELECT *, MATCH (page_content) 
                AGAINST ('{$match}' IN BOOLEAN MODE) 
                AS relevance FROM search WHERE MATCH (page_content) 
                AGAINST ('{$match}' IN BOOLEAN MODE)
                ORDER BY relevance DESC";
    $matchQ2 = "SELECT *, MATCH (page_content) 
                AGAINST ('{$match2}' IN BOOLEAN MODE) 
                AS relevance FROM search WHERE MATCH (page_content) 
                AGAINST ('{$match2}' IN BOOLEAN MODE)
                ORDER BY relevance DESC";
}

if(count($like) >= 1){
    $count = 1;
    $likeQ = "SELECT * FROM search WHERE ";
    foreach ($like as $key => $value) {
        if(count($like) > $count){
            $likeQ .= "page_content LIKE '{$value}' OR ";
        }else{
            $likeQ .= "page_content LIKE '{$value}'";
        }
        $count++;
    }
}
          
if($matchQ != FALSE){                
  
    $matchQ_prep = queryMysql($matchQ);
    $matchQ2_prep = queryMysql($matchQ2);
    while ($row = mysql_fetch_assoc($matchQ_prep)){
        if(count($row) > 0){
            array_push($_array_match, $row);
        }else{
            echoPre('There is no results from MATCH query');
        }
    }
    while ($row2 = mysql_fetch_assoc($matchQ2_prep)){
        if(count($row2) > 0){
            array_push($_array_match, $row2);
        }else{
            echoPre('There is no results from MATCH2 query');
        }
    }
}

if($likeQ != FALSE){
    $likeQ_prep = queryMysql($likeQ);
    
    while($row3 = mysql_fetch_assoc($likeQ_prep)){
        if(count($row3) > 0){
            array_push($_array_like , $row3);
        }else{
            echoPre('There id no results from LIKE query');
        }
    }
}

if( count($_array_match) <= 0 && count($_array_like) <= 0 ){
     echo "NO"; exit;
}else if(count($_array_match) > 0 && count($_array_like) > 0 ){
    $_array_match = array_unique($_array_match,SORT_REGULAR );
    $_ARRAY_RESULTS = array_merge($_array_match, $_array_like);
}else if(count($_array_match) > 0 && count($_array_like <= 0)){
    $_array_match = array_unique($_array_match,SORT_REGULAR );
    foreach ($_array_match as $key => $value) {
        array_push($_ARRAY_RESULTS, $value);
    }
}else if(count($_array_like > 0)) {
    foreach ($_array_like as $key => $value) {
        array_push($_ARRAY_RESULTS, $value);
    }
}


foreach ($_ARRAY_RESULTS as $value) {
    if(!is_numeric($value)){
        $return .= '<div class="divPre row searchPadding searchMargin">';
        $return .= '<div class="col-xs-12 col-sm-8"><a href="'.$value['page_url'].'"><img src="_images/video_thumb_homepage_01.png" class="col-xs-6 col-sm-5 img-responsive" /></a>';
        $return .= '<p class="darkvader"><p>'.$value['real_content'].'</p></div><br />';
        
        $return .= '<div class="col-xs-10"><address>'.$value['full_address'].'</address></div>';
        $return .= '<div class="col-sm-offset-1 col-xs-3"><a href="tel:'.$value['phone'].'">Call</a></div>';                    
        $return .= '<div class="col-xs-3" style="text-align: center;">';
        //$return .= '<a href="geo:'.$value['lat'].','.$value['lng'].'" target="_blank">';
        $return .= '<a href="http://maps.apple.com/?q='.urlencode($value['full_address']).'">';
        $return .= 'Directions</a></div>';        
        $return .= '<div class="col-xs-3" style="text-align: right;"><a href='.$value['page_url'].'>Website</a></div>';
        $return .= '</div>';
        
    }
}
/* RETURNS */
echo $return;
?>


