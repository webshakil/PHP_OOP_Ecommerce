<?php
/**
*Session Class
**/
class Session{
 public static function init(){
  if (version_compare(phpversion(), '5.4.0', '<')) {
        if (session_id() == '') {
            session_start();
        }
    } else {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
 }

 public static function set($key, $val){
  $_SESSION[$key] = $val;
 }

 public static function get($key){
  if (isset($_SESSION[$key])) {
   return $_SESSION[$key];
  } else {
   return false;
  }
 }

 public static function checkSession(){
  self::init();
  if (self::get("login")== false) {
   self::destroy();
   header("Location:login.php");
  }
 }

 public static function checkLogin(){
  self::init();
  if (self::get("login")== true) {
   header("Location:index.php");
  }
 }

 public static function destroy(){
  session_destroy();
  header("Location:login.php");
 }
}
?>
Use for Login Controller:
<?php
/**
* Login Controller
*/
class Login extends DController{
 
 public function __construct(){
  parent::__construct();
 }

 public function Index(){
  $this->login();
 }

 public function login(){
  Session::init();
  if (Session::get("login") == true) {
   header("Location:".BASE_URL."/Admin");
  }
  $this->load->view("login/login");
 }

 public function loginAuth(){
  $table = "tbl_user";
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $loginModel = $this->load->model("LoginModel");
  $count = $loginModel->userControl($table, $username, $password);
  if ($count > 0) {
    $result = $loginModel->getUserData($table, $username, $password);
    Session::init();
    Session::set("login", true);
    Session::set("username", $result[0]['username']);
    Session::set("userId", $result[0]['id']);
    Session::set("level", $result[0]['level']);
          header("Location:".BASE_URL."/Admin");
  } else {
   header("Location:".BASE_URL."/Login");
  } 
 }

 public function logout(){
  Session::init();
  Session::destroy();
  header("Location:".BASE_URL."/Login");
 }
}
?>