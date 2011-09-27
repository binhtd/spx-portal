<?php
require_once 'Zend/View/Interface.php';

class Zend_View_Helper_ShowMenuDependingRole
{
	public $view;
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
	
	public function showMenuDependingRole()
	{	
		$auth = Zend_Auth::getInstance();
		if( $auth->hasIdentity()){
			$this->view->UserRole = $auth->getIdentity()->UserRole;			
		}
		
		return $this->view->render('index/_show-menu-depending-role.phtml');
	}
}