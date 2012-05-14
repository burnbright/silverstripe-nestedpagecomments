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
	
	function NestedReplies(){
		$spamfilter = isset($_GET['showspam']) ? '' : "\"IsSpam\" = 0";
		$unmoderatedfilter = Permission::check('CMS_ACCESS_CommentAdmin') ? '' : "AND \"NeedsModeration\" = 0";
		$notreplyfilter = " AND ReplyToID = 0";
		return $this->owner->Children("$spamfilter $unmoderatedfilter");
	}

	//onBeforeWrite
	//delete all the children, if there are any
}