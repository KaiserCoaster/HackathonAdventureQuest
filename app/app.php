<?php

class App {
    
    private $db;
    
    function home($f3) {
        $f3->set('pagetitle','Home');
		$f3->set('template','home');
        $f3->set('name', 'what');
    }
    
    function beforeroute($f3) {
        $f3->set('rendertemplate', true);
    }
    
    function afterroute($f3) {
        if($f3->get('rendertemplate'))
            echo Template::instance()->render('view/layout.html');
	}
    
    function __construct() {
		$f3=Base::instance();
		$db=new DB\SQL($f3->get('db'), $f3->get('dbuser'), $f3->get('dbpass'));
		$this->db=$db;
	}

}

?>