<?php
class Welcome extends Controller {
    public function index() {
        $_SESSION['controller'] = 'welcome'; 
        $this->view('welcome/index');
    }
}