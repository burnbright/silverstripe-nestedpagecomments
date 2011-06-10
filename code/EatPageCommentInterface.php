<?php
class NestedPageCommentInterfaceDecorator extends DataObjectDecorator{
	
	function updatePageCommentForm(&$form){
		
		
		if(isset($_GET['replyto']) && $replyto = $_GET['replyto'])
			$form->Fields()->push(new HiddenField('ReplyToID','Reply To',$replyto));
		
		
	}
	
}