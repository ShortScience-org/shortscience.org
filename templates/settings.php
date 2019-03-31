<?php require("header.php");?>
<?php require("menu.php");?>
<?php require("orcid.php");?>
<?php global $SCRIPT_VERSION ?>

<br>
<div class="container" style="max-width:700px; margin:0 auto;">


<ul class="nav nav-tabs">

<li role="presentation" <?=($tab == "")?'class="active"':''?>><a href="">Settings</a></li>

</ul>


<?php if ($tab == "" && $currentuser->userid == $user->userid){?>

<div id="mysettings">

<style>

</style>
<table class="table" style="">
<tr>
<td style="border-top: none;">
Userid:
</td>
<td style="border-top: none;">
<input name="userid" id="userid" type="hidden" value="<?=$user->userid?>"/>
<?=$user->userid?>
</td>
</tr>

<tr>
<td>
Username:
</td>
<td>
<input name="username" id="username" type="text" class="form-control"  value="<?=$user->username?>" disabled/>
</td>
</tr>

<tr>
<td>
Email:
</td>
<td>
<input name="email" id="email" type="text" class="form-control"  value="<?=$user->email?>" disabled/>
</td>
</tr>

<tr>
<td>
Email Preferences:
</td>
<td>
Receive Comments: <input name="email_receive_comments" id="email_receive_comments" type="checkbox"   <?=$user->email_receive_comments?"checked":""?>/>
</td>
</tr>

<tr>
<td>
Account Created:
</td>
<td>
<?=$user->added?>
</td>
</tr>

<tr>
<td>
Last Login:
</td>
<td>
<?=$user->last_login?>
</td>
</tr>


<tr>
<td>
Display Name:
</td>
<td>
<input name="displayname" id="displayname" type="text" class="form-control" value="<?=htmlspecialchars($user->displayname)?>" autocomplete="off">
</td>
</tr>

<tr>
<td>
ORCID <small><a href="http://orcid.org/">[info]</a></small>
</td>
<td>
<input name="orcid" id="orcid" type="text" class="form-control" value="<?=$user->orcid?>" autocomplete="off">
</td>
</tr>

<tr>
<td>
Description:
</td>
<td>
<textarea rows=5 name="description" id="description" type="text" class="form-control" ><?=$user->description?></textarea>
</td>
</tr>


<tr>
<td>
Change Password:
</td>
<td>
<form action="javascript:void(0);">
<input name="password" id="password" type="password" class="form-control" autocomplete="off">
</form>
</td>
</tr>


<tr>
<td>
Export Data:
</td>
<td>
<a href="./export"><input type="button" class="btn btn-info" value="Export Data"/></a>
</td>
</tr>

</table>

  <div id="errorbox">
  </div>

<input id="savesettings" type="button" class="btn btn-default pull-right" value="Save Changes"/>
</div>
<?php }?>




</div>
</div>

<script src="./res/js/standard.js?v=<?=$SCRIPT_VERSION?>"></script>


<?php require("footer.php");?>