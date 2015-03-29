<?php

class App {
    
    private $db;
    
    function home($f3) {
        self::quest($f3, array('id'=>1));
    }
    
    function quest($f3, $args) {
        $id = $args['id'];
        $quest = self::getQuest($id);
        $f3->set('pagetitle','HackQuest['.$id.']');
        if($quest != null) {
            $f3->set('template','view/home.html');
            $f3->set('message', $quest->message);
        }
        else {
            $f3->set('template','view/error.html');
        }
    }
    
    function getQuest($id) {
        $db = $this->db;
		$quest = new DB\SQL\Mapper($db,'questTree');
        $quest->load(array('id=?', $id));
        if($quest->dry()) {
            return null
        }
        return $quest;
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