<?php $currentuser = getcurrentuser();?>

<nav class="navbar navbar-inverse " style="margin:0px;background-image: url(res/albert-ss.jpg); background-repeat: no-repeat; background-color:black;">
  <div class="container">
  <a class="navbar-brand" href="/" style="float:left;width:300px;margin-left:-100px">
  </a>
	
      <ul class="nav navbar-nav navbar-right">
      
      
      	<?php if ($currentuser->userid == -1){?>
        <li style="float:right;"><a href="./login">Login</a></li>
        <?php }else{?>
          <li class="dropdown" style="float:right;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=get_gravatar($currentuser->email,20,identicon,'g',true,[style=> "border-radius: 50%;padding:0px;margin:0px;" ])?> <?=$currentuser->username?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="./user">My Profile</a></li>
            <li><a href="./settings">Settings</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="./logout">Log Out</a></li>
          </ul>
        </li>
        <?php }?>

      </ul>
      
      
<div class="navbar-form navbar-right" style="border: none;"/>

      	<a class="" style="" href="./venue">
		<input class="btn btn-default" type="submit" style="" value="Venues"/>
		</a>
		
		<a class="" style="" href="./random">
		<input class="btn btn-default" type="submit" style="" value="Random"/>
		</a>

	
      	<a class="" style="" href="./about">
		<input class="btn btn-default" type="submit" style="" value="About"/>
		</a>

      	<a class="" style="" href="./">
		<input class="btn btn-default" type="submit" style="" value="Home"/>
		</a>
		
</div>


      
     <form class="navbar-form navbar-right" role="search" action="search" style="border: none;">
	  <div class="input-group">
	    <input type="text" name="term" class="form-control" placeholder="Search for a paper..." value="<?=htmlspecialchars($term)?>">
	    <span class="input-group-btn">
        	<input class="btn btn-default" type="submit" style="" value="Go!"/>
        	
      	</span>
	  </div>
	  
	</form>
	
    </div>
</nav>