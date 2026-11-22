<?php 

  require_once __DIR__ . '/env_loader.php';

  $hname = getenv('DB_HOST');
  $uname = getenv('DB_USER');
  $pass  = getenv('DB_PASS');
  $db    = getenv('DB_NAME');

  $port  = (int)(getenv('DB_PORT') ?: 3306);
  $use_ssl = in_array(strtolower((string)getenv('DB_SSL')), ['1','true','yes','on'], true);

  mysqli_report(MYSQLI_REPORT_OFF);
  $con = mysqli_init();
  $flags = 0;
  if($use_ssl){
    $ca = getenv('DB_SSL_CA') ?: null; // optional path to CA file
    if(!$ca){
      foreach(['/etc/pki/tls/certs/ca-bundle.crt','/etc/ssl/certs/ca-certificates.crt','/etc/ssl/cert.pem'] as $bundle){
        if(is_file($bundle)){ $ca = $bundle; break; }
      }
    }
    mysqli_ssl_set($con, null, null, $ca, null, null);
    $flags = MYSQLI_CLIENT_SSL;
  }
  if(!@mysqli_real_connect($con,$hname,$uname,$pass,$db,$port,null,$flags)){
    error_log('DB connect failed: '.mysqli_connect_error());
    $con = false;
  }
  else{
    mysqli_set_charset($con,'utf8mb4');
  }

  if(!$con){
    http_response_code(500);
    die("Cannot connect to the database. Please try again later.");
  }

  function filteration($data){
    foreach($data as $key => $value){
      $value = trim($value);
      $value = stripslashes($value);
      $value = strip_tags($value);
      $value = htmlspecialchars($value);
      $data[$key] = $value;
    }
    return $data;
  }

  function selectAll($table)
  {
    $con = $GLOBALS['con'];
    $res = mysqli_query($con,"SELECT * FROM $table");
    return $res;
  }

  function select($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      if (!empty($values) && !empty($datatypes)) {
      mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    }
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Select");
      }
    }
    else{
      die("Query cannot be prepared - Select");
    }
  }

  function update($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      // mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if (!empty($values) && !empty($datatypes)) {
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
      }
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Update");
      }
    }
    else{
      die("Query cannot be prepared - Update");
    }
  }

  function insert($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      if (!empty($values) && !empty($datatypes)) {
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
     }
      // mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Insert");
      }
    }
    else{
      die("Query cannot be prepared - Insert");
    }
  }

  function delete($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {

      if (!empty($values) && !empty($datatypes)) {
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
     }
      // mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Delete");
      }
    }
    else{
      die("Query cannot be prepared - Delete");
    }
  }

?>
