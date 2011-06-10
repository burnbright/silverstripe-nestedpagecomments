<% if Children %>
	<ul>
		<% control Children %>
			<li class="child comment pos-$Pos $FirstLast $EvenOdd<% if IsStaffComment %> staff<% end_if %>">
				<% include PageCommentInterface_singlecomment %>
			</li>
		<% end_control %>
	</ul>
<% end_if %>