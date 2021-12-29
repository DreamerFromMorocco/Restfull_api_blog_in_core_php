<?php

$url = $_SERVER['REQUEST_URI'];



// checking if slash is first character in route otherwise add it
if(strpos($url,"/") !== 0){
    $url = "/$url";
}
//echo($url);
$urlArr = explode("/", $url);
 //echo  json_encode($urlArr) ;
$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);
header("Content-Type:application/json");

if($url == '/posts' && $_SERVER['REQUEST_METHOD'] == 'GET') {
 $posts = getAllPosts($dbConn);
 echo json_encode($posts);
}
if(preg_match("/posts\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    $postId = $matches[1]; 
    $post = getPost($dbConn, $postId);
    //echo var_dump($matches) ;
    echo json_encode($post);
}
if($url == '/posts' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST;
    $postId = addPost($input, $dbConn);
    if($postId){
        $input['id'] = $postId;
        $input['link'] = "/posts/$postId";
    }
   
    echo json_encode($input);
   }
   function getPost($db, $id) {
    $statement = $db->prepare("SELECT * FROM posts where id=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}
//Code to update post, if /posts/{id} and method is PATCH

if(preg_match("/posts\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PATCH'){
    $input = $_GET;
    $postId = $matches[1];
    updatePost($input, $dbConn, $postId);

    $post = getPost($dbConn, $postId);
    //echo json_encode($input);
    //echo json_encode($postId);
    echo json_encode($post);
   
}

/**
 * Get fields as parameters to set in record
 *
 * @param $input
 * @return string
 */
function getParams($input) {
    $allowedFields = ['title', 'status', 'content', 'user_id'];

    $filterParams = [];
    foreach($input as $param => $value){
        if(in_array($param, $allowedFields)){
            array_push($filterParams,"$param=:$param");
        }
    }
   // echo json_encode($input);
    //echo json_encode(implode(", ", $filterParams));
    return implode(", ", $filterParams);
}


/**
 * Update Post
 *
 * @param $input
 * @param $db
 * @param $postId
 * @return integer
 */
function updatePost($input, $db, $postId){

    $fields = getParams($input);
   // echo json_encode($fields);
    $sql = "
          UPDATE posts 
          SET $fields 
          WHERE id=:id
           ";

    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $postId);
    bindAllValues($statement, $input);

    $statement->execute();

    return $postId;
}
//if url is like /posts/{id} (id is integer) and method is DELETE

if(preg_match("/posts\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){ 
    $postId = $matches[1];
    deletePost($dbConn, $postId);

    echo json_encode([
        'id'=> $postId,
        'deleted'=> 'true'
    ]);
}

/**
 * Delete Post record based on ID
 *
 * @param $db
 * @param $id
 */
function deletePost($db, $id) {    
    $statement = $db->prepare("DELETE FROM posts where id=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();
}
;;
function getAllPosts($db) {
 $statement = $db->prepare("SELECT * FROM posts");
 $statement->execute();
 $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
 return $statement->fetchAll();
}
function addPost($input, $db){
    $sql = "INSERT INTO posts 
    (title, status, content, user_id) 
    VALUES 
    (:title, :status, :content, :user_id)";
   
    $statement = $db->prepare($sql);
   
    // $statement->bindValue(':title', $input['title']);
    // $statement->bindValue(':status', $input['status']);
    // $statement->bindValue(':content', $input['content']);
    // $statement->bindValue(':user_id', $input['user_id']);
     bindAllValues($statement, $input);

   
    $statement->execute();
   
    return $db->lastInsertId();
   }
   function bindAllValues($statement, $params){
    $allowedFields = ['title', 'status', 'content', 'user_id'];

    foreach($params as $param => $value){
        if(in_array($param, $allowedFields)){
            $statement->bindValue(':'.$param, $value);
        }
    }
    
    return $statement;
}