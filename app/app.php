<?php

class App {
    
    private $db;
    
    function home($f3) {
        self::quest($f3, array('id'=>1));
    }
    
    function quest($f3, $args) {
        $id = $args['id'];
        $quest = self::getQuest($id);
        if($quest != null) {
            $f3->set('pagetitle','HackQuest['.$quest->level.']');
            $f3->set('template','view/quest.html');
            $f3->set('message', $quest->message);
            $f3->set('qa', self::questOptionURL($id, 'a', $quest->a));
            $f3->set('qb', self::questOptionURL($id, 'b', $quest->b));
            $f3->set('qc', self::questOptionURL($id, 'c', $quest->c));
        }
        else {
            $f3->set('pagetitle','HackQuest');
            $f3->set('template','view/error.html');
        }
    }
    
    function make($f3, $args) {
        $parent = $args['parent'];
        $option = $args['option'];
        $quest = self::getQuest($parent);
        $f3->set('pagetitle','HackQuest['.($quest->level+1).']');
        $f3->set('template','view/make.html');
        $action = "";
        switch($option) {
            case 'a':
                $action = "You have chosen to get serious";
                break;
            case 'b':
                $action = "You have chosen to give up";
                break;
            case 'c':
                $action = "You have chosen the Magic Hat!";
                break;
        }
        $f3->set('message', $action);
    }
    
    function makePost($f3) {
        $f3->set('rendertemplate', false);
        $db = $this->db;
        $parent = $f3->get('PARAMS.parent');
        $option = $f3->get('PARAMS.option');
        $message = $f3->get('POST.message');
        $pquest = self::getQuest($parent);
        $quest = new DB\SQL\Mapper($db, 'questTree');
        $quest->message = $message;
        $quest->a = 0;
        $quest->b = 0;
        $quest->c = 0;
        $quest->level = $pquest->level+1;
        $quest->save();
        switch($option) {
            case 'a':
                $pquest->a = $quest->id;
                break;
            case 'b':
                $pquest->b = $quest->id;
                break;
            case 'c':
                $pquest->c = $quest->id;
                break;
        }
        $pquest->save();
        header("Location: /" . $quest->id);
    }
    
    function getQuest($id) {
        $db = $this->db;
		$quest = new DB\SQL\Mapper($db,'questTree');
        $quest->load(array('id=?', $id));
        if($quest->dry()) {
            return null;
        }
        return $quest;
    }
    
    function questOptionURL($parentID, $option, $optionID) {
        if($optionID == 0)
            return "make/" . $parentID . "/" . $option;
        else
            return $optionID;
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