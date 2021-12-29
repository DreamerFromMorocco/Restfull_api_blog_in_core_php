<?php 
//  POST /posts/1/comments 
//  GET /posts/1/comment/1 or GET /comment/1
//  PATCH /comment/1?comment="Modified%20Awesome%20Comment'
//  DELETE /comments/1 
//GET /posts/1/comments -> listing all comments 
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,"/") !== 0){
    $url = "/$url";
}
$DbInstance = new DB() ;
$DbConn=$DbInstance->connect($db);
header("Content-Type:application/json");
if($url == '/comments' && $_SERVER['REQUEST_METHOD']=='GET'){
    $allComments =getAllComments($DbConn);
    echo json_encode($allComments);
}
function getAllComments($DbConn){
$sql ="SELECT * FROM comments";
$statement = $DbConn->prepare($sql);
$statement->execute();
$statement->setFetchMode(PDO::FETCH_ASSOC);
return $statement->FetchAll();
}
if(preg_match("/comments\/([0-9]+)/",$url,$matches) && $_SERVER['REQUEST_METHOD']=='GET'){
    $commentId = $matches['1'];
    $comment = getComment($DbConn,$commentId);
    echo json_encode($comment);
}

function getComment($DbConn,$commentId){
    $sql= "SELECT * FROM comments WHERE id=:id";
    $statement=$DbConn->prepare($sql);
    $statement->bindValue(':id',$commentId);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
   
}
if($url == '/comments'  &&  $_SERVER['REQUEST_METHOD']== 'POST'){
    $input = $_POST;
    $lastInsertId = createComment($DbConn,$input);
    if($lastInsertId){
        $input['id']= $lastInsertId ;
        $input['link']="comments/$lastInsertId" ;
    }
   echo json_encode($input);
}
function createComment($DbConn,$input){
    $sql ="INSERT INTO comments(comment,post_id,user_id) VALUES (:comment,:post_id,:user_id)";
    $statement =$DbConn->prepare($sql);
    bindAllValues($statement,$input);
    $statement->execute();
    return $DbConn->lastInsertId();
}
function bindAllValues($statement,$input){
    $allowedFields = ['comment','post_id','user_id'];
    foreach($input as $key=>$value){
        if(in_array($key,$allowedFields)){
            $statement->bindValue(':'.$key,$value );
        }
    }
    return $statement;
}
 if(preg_match("/comments\/([0-9]+)/",$url,$matches) && $_SERVER['REQUEST_METHOD']== 'PATCH'){
    $input = $_GET;
    $commentId= $matches[1];
    updateComment($input,$commentId,$DbConn);
    $comment = getComment($DbConn,$commentId);
    echo json_encode($comment);
 }
 function updateComment($input,$commentId,$DbConn){
 $Fields = getParams($input);
 //var_dump($Fields);
 $sql = "UPDATE  comments SET $Fields where  id=:id";
 $statement=$DbConn->prepare($sql);
 $statement->bindValue(':id',$commentId);
 bindAllValues($statement,$input);
 $statement->execute();
 return $commentId;
 }
 function getParams($input){
 //{key:value}
 $allowedFields = ['comment','post_id','user_id'];
 $Array= [];
 foreach ($input as $key=>$value){
   if(in_array($key,$allowedFields)){
    $Array[]="$key=:$key";
   }
 }
 return implode(', ',$Array);
 }
 if(preg_match("/comments\/([0-9]+)/",$url,$matches) && $_SERVER['REQUEST_METHOD']== 'DELETE'){
     $commentID =$matches[1];
     $deleted=deletePost($commentID,$DbConn);
    
        echo  json_encode (
            [
                'id'=> $commentID,
                'Deleted'=>true
            ]
            );
    
 }
 function deletePost($commentID,$DbConn){
     $sql ='DELETE FROM comments WHERE id=:id';
     $statement=$DbConn->prepare($sql);
     $statement->bindValue('id',$commentID);
     $statement->execute();
     
 }