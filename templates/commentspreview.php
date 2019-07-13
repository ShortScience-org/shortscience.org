<?php 
//$comment->text
//$comment->username
//$comment->email
//$comment->canremove
//$comment->preview

//print_r($comments);

$commenturl = "/paper?bibtexKey=".$vignette->paperid."#".(($vignette->anon == 0)?$vignette->username:"anon")."comments";
?>
<?php if (sizeof($comments) > 0){?>
<a href="<?=$commenturl?>">
<div class="pull-right" style="">
<span class="badge"><?=(0!=sizeof($comments))?sizeof($comments):""?> Comments</span>
<?php 
foreach ($comments as $comment){?>
<?=get_gravatar($comment->email,20,"identicon",'g',true,["style"=> "border-radius: 50%;height:20px;" ])?>
<?php }?>
</div>
</a>
<?php }?>