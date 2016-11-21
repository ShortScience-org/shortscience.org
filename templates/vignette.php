<?php 
//$preview
//passed
//$showcomments


$shareurl = "http://www.shortscience.org/paper?bibtexKey=".$vignette->paperid."#".(($vignette->anon == 0)?$vignette->username:"anon");
$sharetext = "Summary of ".$paper->title;
$shareanchor = base64_encode($vignette->paperid).(($vignette->anon == 0)?$vignette->username:"anon");
$shareanchor = str_replace("=","",$shareanchor);


//print_r($vignette)?>

<style>
.vignette-right a{
word-break: break-all;
}
</style>
	<div id="<?=($vignette->anon == 0)?$vignette->username:"anon"?>" class="vignette <?=($v>0 && !$preview)?"vignette-alt":"vignette-top"?> <?=($preview)?"vignette-preview":""?>" style="width:100%;<?=($v>0 && !$preview)?"opacity:0.3;":""?>; margin-bottom:20px;">
	  
	  <table style="table-layout:fixed;width:100%;">
	  <tr>
	  <td style="text-align:center;vertical-align:top ;width:35px;">
	  <div class="vignette-left">
	    
	    <div style="width:35px;">
	    <center>
	    <div class="row userblock">
	    
	      <?php if ($vignette->anon == 1){?>
	      <span class="glyphicon glyphicon-user" style="font-size:2em;" aria-hidden="true" alt="Name hidden"></span>
	      <?php } else {?>	      
	      <a href="user?name=<?=$vignette->username?>" class="usericonpop" data-content="Username: <?=$vignette->username?><br>Posted: <?=time_elapsed_string($vignette->added)?> <?php if ($vignette->added != $vignette->edited){?><br>Edited: <?=time_elapsed_string($vignette->edited)?><?php }?>"
	      title='
	      <center><a href="user?name=<?=$vignette->username?>"><?=get_gravatar($vignette->email,150,identicon,"g",true,[style=> "border-radius: 50%;height:150px;" ])?><br>
	      <span style="font-size:15pt"><?=($vignette->displayname == "")?$vignette->username:htmlspecialchars($vignette->displayname)?></span>
	      </a></center>' >
	      <?=get_gravatar($vignette->email,30,identicon,'g',true,[style=> "border-radius: 50%;height:30px;" ])?>
	      </a>
	      <?php }?>
	      <div class="row"> 
		   	  <?php if ($vignette->priv == 1){?>
		      <span class="glyphicon glyphicon-lock" style="font-size:2em;" aria-hidden="true" alt="Only you can see this"></span>
		      <?php }?>
		  </div>
		</div>
	      
	    <div class="row voteblock" style="width:40px;font-size:1.5em;padding-top:15px;">
	      	  <input type="hidden" class="paperid" value="<?=$vignette->paperid?>"/>
		      <input type="hidden" class="userid" value="<?=$vignette->userid?>"/>
		      <input type="hidden" class="myvote" value="<?=$vignette->myvote?>"/>
		      <input type="hidden" class="voteruserid" value="<?=$currentuser->userid?>"/>
			      <span class="votebtn votebtn-up" style="cursor:hand;cursor: pointer;<?=($vignette->myvote == 1)?"color:blue;":""?>" title="Vote up">
			      	<span  class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
			      </span>
			   <div class="row" title="Current score based on community votes">
			      <span class="votevalue" ><?=$vignette->vote?></span>
			   </div>
			      <span class="votebtn votebtn-down" style="cursor:hand;cursor: pointer;<?=($vignette->myvote == -1)?"color:red;":""?>" title="Vote down">
					<span  class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
			      </span>
		 </div>
		  
		  <div class="row toolblock" style="padding-top:10px;">

		  	<a class="viewsource" style="cursor:hand;cursor: pointer;font-size:1.5em" title="View the source of this summary">
		  	<span  class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
		  	<br>
		  	<a href="javascript:void($('#<?=$shareanchor?>').toggle());" title="Share" style="font-size:1.5em;">
		  	<span  class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
		  	<br>
		  	<a href="mailto:joseph@josephpcohen.com?subject=ShortScience.org Summary Report&body=I am reporting the summary: <?=$shareurl?>" title="Report" style="font-size:1.5em;">
		  	<span  class="glyphicon glyphicon-flag" aria-hidden="true"></span></a>
		  	

		  	

<!-- 		  	<a href="#"> -->
<!-- 		  	<span  class="glyphicon glyphicon-tags" aria-hidden="true"></span> -->
<!-- 		  	</a> -->
		  	
<!-- 		  	<a href="#"> -->
<!-- 		  	<span  class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> -->
<!-- 		  	</a> -->
		  </div>
	      </center>
	      </div>
	  </div>	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  </td>
	  <td class="vignette-right" style="overflow: auto;width:100%;">


	  	<?php 
		require("shareblock.php")
		?>
	  	<div class="panel panel-default" style="min-height:250px;margin-bottom:5px;">
		  	<pre class="source panel-body entry <?=($preview)?'userentry':'';?>" 
		  		 style="width:100%;min-height:250px;max-height:1000px;overflow:auto; white-space: pre-wrap;word-wrap: break-word; display:none;margin:0px"><?=htmlspecialchars($vignette->text)?></pre>
			<div class="rendered panel-body <?=($preview)?'userentry':'';?>" style="width:100%;min-height:250px;max-height:1000px;overflow:auto;"><center><img style="padding-top:70px;" alt="Loading..." src="https://i.imgur.com/yoS0cXm.gif"/></center></div>
		</div>

		
		<?php 
		$comments = getComments($vignette->paperid, $vignette->userid);

		if (!$showcomments){
			require("commentspreview.php");
		}else{
		?>
			<div id="<?=($vignette->anon == 0)?$vignette->username:"anon"?>comments" class="col-sm-offset-3 col-sm-9" style="">
			
			<?php 

			foreach ($comments as $comment){
				//print_r($comment);
				$comment->canremove = $comment->userid == $currentuser->userid;
				require("comment.php");
			
			}	
			?>
				
	
		    <div class="newcomment">
    
		    	Your comment:
				 <div class="input-group" style="width:100%">
				 	<input type="hidden" class="paperid" value="<?=$vignette->paperid?>">
				 	<input type="hidden" class="summaryuserid" value="<?=$vignette->userid?>">
				 	
					<textarea class="commententry form-control custom-control" rows="2" style="resize:none"></textarea>     
					<span class="<?=($currentuser->userid == -1)?"":"newcommentsubmit"?> input-group-addon btn btn-default" <?=($currentuser->userid == -1)?"disabled":""?>><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></span>
				</div>
		    
		    
			<?php 
			
			$comment = (object)[];
			$comment->text = "";
			$comment->email = $currentuser->email;
			$comment->username = $currentuser->username;
			$comment->canremove = false;
			$comment->preview = true;
			
			require("comment.php")?>
			 </div>
		    
		    
		    <div>
		    </div>
		    </div>
	    
	    <?php }?>
	  </div>
	  
	  </td>
	  </tr>
	  </table>
	</div>
	