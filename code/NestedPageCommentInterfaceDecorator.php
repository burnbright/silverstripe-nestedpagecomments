<?php
class NestedPageCommentInterfaceDecorator extends DataObjectDecorator{
	
	static $order_comments_by = 'Created ASC';
	
	function updatePageCommentForm(&$form){		
		
		$hf = new HiddenField('ReplyToID','Reply To');
		
		if(isset($_GET['replyto']) && is_numeric($_GET['replyto']) && $comment = DataObject::get_by_id('PageComment',$_GET['replyto'])){
			$hf->setValue($comment->ID);
			$clear = " <a href=\"".Controller::curr()->Link()."#leavereply\">clear</a>";
			$form->Fields()->insertBefore(new LiteralField('replymessage',"Replying to ".$comment->Name.$clear),'Name');
		}
		
		$form->Fields()->push($hf);
		
		//TODO: add validation that checks comment exists, and 
		
	}
	
	
	function NestedComments() {
		// Comment limits
		$limit = array();
		$limit['start'] = isset($_GET['commentStart']) ? (int)$_GET['commentStart'] : 0;
		$limit['limit'] = PageComment::$comments_per_page;
		
		$spamfilter = isset($_GET['showspam']) ? '' : "AND \"IsSpam\" = 0";
		$unmoderatedfilter = Permission::check('CMS_ACCESS_CommentAdmin') ? '' : "AND \"NeedsModeration\" = 0";
		$notreplyfilter = " AND ReplyToID = 0";
		
		$order = self::$order_comments_by;
		$comments =  DataObject::get("PageComment", "\"ParentID\" = '" . Convert::raw2sql($this->owner->page->ID) . "' $spamfilter $unmoderatedfilter $notreplyfilter", $order, "", $limit);
		
		if(is_null($comments)) {
			return;
		}
		
		// This allows us to use the normal 'start' GET variables as well (In the weird circumstance where you have paginated comments AND something else paginated)
		$comments->setPaginationGetVar('commentStart');
		
		return $comments;
	}
	
}