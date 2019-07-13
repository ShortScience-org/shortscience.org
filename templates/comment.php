<?php 
//$comment->text
//$comment->username
//$comment->email
//$comment->canremove
//$comment->preview

//print_r($comment);
?>

<div class="row comment" style="<?=($comment->preview)?"display:none;padding-top:10px":""?>;overflow: auto; overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;">
	<input class="commentid" type="hidden" value="<?=$comment->id?>"/>
	<table style="width:100%">
	<tr>
	<td style="vertical-align: top;">
	<center>
		<a href="user?name=<?=$comment->username?>" class="commentusericonpop" 
	      placement="top"
	      title='
	      <center><a href="user?name=<?=$comment->username?>"><?=get_gravatar($comment->email,150,"identicon","g",true,["style"=> "border-radius: 50%;height:150px;" ])?><br>
	      <span style="font-size:15pt"><?=($comment->displayname == "")?$comment->username:htmlspecialchars($comment->displayname)?></span>
	      </a></center>'
	      data-content="Username: <?=$comment->username?><br>Posted: <?=time_elapsed_string($comment->added)?>"
	      >
  		<?=get_gravatar($comment->email,20,"identicon",'g',true,["style"=> "border-radius: 50%;height:20px;" ])?></a>
      	

	</center>
	</td>
	<td style="width:100%">
	     <?php if ($comment->canremove){?>
		<span class="delcommentbtn" style="cursor:hand;cursor: pointer;float:right;"><span style="font-size: 1.5em" class="glyphicon glyphicon-remove" aria-hidden="true"></span></span>
		<?php }?>
	<div class="comment-text" style="padding-left:10px;"><?=htmlspecialchars($comment->text)?></div>
	</td>
	</tr>
	</table>
<? if (!$comment->preview){?>
<hr style="margin:5px;">
<?php }else if (getcurrentuser()->userid == -1){?>
<div class="alert alert-danger" role="alert" style="margin:10px;">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  You must log in before you can post this comment!
</div>
<?php } else {?>
<div class="alert alert-warning" role="alert" style="margin:10px;">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  This is a draft. The comment is not posted yet.
</div>
<?php }?>
</div>