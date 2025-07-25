<?php
class Logout extends Controller {

public function index() {
if (session_status() === PHP_SESSION_NONE) {
    }
    session_unset();    
    session_destroy();   
    header("Location: /login");  
  exit;
  }
}