<?php

class NestedPageCommentDecorator extends DataObjectDecorator{
	
	function extraStatics(){
		return array(
			'db' => array(
				'Email' => 'Varchar'
			),
			'has_one' => array(
				'ReplyTo' => 'PageComment'
			),
			'has_many' => array(
				'Children' => 'PageComment'
			)
		);
	}
	
	function GravatarHash(){
		if($this->owner->Email)
			return md5(strtolower(trim($this->owner->Email)));
		if($member = $this->owner->AuthorID)
			return md5(strtolower(trim($this->owner->Author()->Email)));
		return  md5($this->owner->SessionID);
	}
	
	function IsStaffComment(){
		return ($this->owner->AuthorID && $this->owner->Author()->isAdmin());		
	}
	
	function ReplyComments(){
		return $this->owner->renderWith('PageCommentChildren');
	}
	

	//onBeforeWrite
	//delete all the children, if there are any

	
}
 
?>
