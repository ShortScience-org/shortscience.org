<?php require("header.php");?>
<?php require("menu.php");?>
<?php global $SCRIPT_VERSION ?>
<?php global $MATHJAX_URL?>

<div class="container" style="max-width:750px; margin:0 auto;">

<div class="row">

<div class="alert" style="background-color: rgb(184, 77, 100); color:white;font-size: 1.2em;">
<table style="width:100%">
<tr>
<td style="width:70px">
<img src="res/cabin.png" style="width:60px;padding:5px;"/>
</td>
<td>
<span style="vertical-align: middle;color:white;">

Welcome to ShortScience.org! <br>

</span>
</td>
<td style="width:180px">
<a target="_blank" href="/rss.xml">
<img src="res/img/rss-icon-s.png" style="width:50px" alt="RSS Feed"/>
</a>
<a target="_blank" href="https://twitter.com/shortscienceorg">
<img src="res/img/sharebuttons/simple/twitter.png" style="width:50px;border-radius:10px;padding:4px" alt="Twitter"/>
</a>
<a target="_blank" href="https://www.facebook.com/shortscienceorg/">
<img src="res/img/sharebuttons/simple/facebook.png" style="width:50px;border-radius:10px;padding:4px" alt="Facebook"/>
</a>
</td>
</tr>
</table>
<div style="margin-bottom:15px;">
<div id="more-home" style="display:none">
<hr>
<ul>
<li>ShortScience.org is a platform for post-publication discussion aiming
to improve accessibility and reproducibility of research ideas.
<li>The website has <?=getStats()[0][1];?> public summaries, mostly in machine learning,
written by the community and organized by paper, conference, and year.
<li>Reading summaries of papers is useful to obtain the perspective and
insight of another reader, why they liked or disliked it, and their attempt to demystify complicated sections.
<li>Also, writing summaries is a good exercise to understand the content of a
paper because you are forced to challenge your assumptions when
explaining it.
<li>Finally, you can keep up to date with the flood of research by reading
the latest summaries on our Twitter and Facebook pages.
</ul>
</div>

<script>

function showmore(){
	$('#showmore-home').hide();
	$('#showless-home').show()
	$('#more-home').show();
	
}

function showless(){
	$('#showmore-home').show();
	$('#showless-home').hide();
	$('#more-home').hide();
	setCookie('moreinfo','moreinfo',9999);
}

$(function(){
if (getCookie("moreinfo") != "moreinfo"){
	showmore();
}
});

</script>
<span style="position:absolute;left:50%;width:100px;margin-left:-50px;font-size:small">
<span id="showmore-home" style=""><a style="color:white;" href="javascript:showmore()"><center><span class="glyphicon glyphicon-info-sign"></span> more</center></a></span>
<span id="showless-home" style="display:none;"><a style="color:white;" href="javascript:showless()"><center><span class="glyphicon glyphicon-info-sign"></span> hide</center></a></span>
</span>
</div>

</div>

<div class="btn-group btn-group-justified" role="group">
	<div class="btn-group" role="group"><button onclick="javascript:changeSection('');" type="button" class="btn <?=($sections=="")?'btn-success':'btn-default'?>">All</button></div>
	<div class="btn-group" role="group"><button onclick="javascript:changeSection('cs');" type="button" class="btn <?=(strpos($sections, 'cs') !== false)?'btn-success':'btn-default'?>">CompSci</button></div>
	<div class="btn-group" role="group"><button onclick="javascript:changeSection('bio');" type="button" class="btn <?=(strpos($sections, 'bio') !== false)?'btn-success':'btn-default'?>">Biology</button></div>
	<div class="btn-group" role="group"><button onclick="javascript:changeSection('ph');" type="button" class="btn <?=(strpos($sections, 'ph') !== false)?'btn-success':'btn-default'?>">Physics</button></div>
</div>

<script>
function updateURLParameter(url, param, paramVal)
{
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL) 
    {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
        if(TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (var i=0; i<tempArray.length; i++)
        {
            if(tempArray[i].split('=')[0] != param)
            {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }        
    }
    else
    {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor  = tmpAnchor[1];

        if(TheParams)
            baseURL = TheParams;
    }

    if(TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function changeSection(section){

	window.location.href = updateURLParameter(window.location.href, "s", section);
}
</script>

<br>

<ul class="nav nav-tabs">

<li role="presentation" <?=($tab == "" || $tab == "popular")?'class="active"':''?>><a href="/?tab=popular&s=<?=$sections?>">Popular (Today)</a></li>
<!-- <li role="presentation" <?=($tab == "popularweek" || $tab == "popularweek")?'class="active"':''?>><a href="/?tab=popularweek&s=<?=$sections?>">Popular (Week)</a></li> -->
<li role="presentation" <?=($tab == "recent")?'class="active"':''?>><a href="/?tab=recent&s=<?=$sections?>">Most Recent</a></li>
<li role="presentation" <?=($tab == "best")?'class="active"':''?>><a href="/?tab=best&s=<?=$sections?>">Highest Rated</a></li>

</ul>
<br>

<?php 
for ($i = 0; $i < sizeof($vignettes); $i++) {
$vignette = $vignettes[$i];
$paper = getPaper($vignette->paperid);

$vignette->myvote = getMyVignettesVote($vignette->paperid, $vignette->userid);

if (!isset($vignette->vote)) $vignette->vote = 0;

//print_r($paperBib);

include("templates/papertitle.php");

include("templates/vignette.php");
?>
<?//print_r($paperBib);?>

<?php }?>

<center>
<p></p><ul class="pagination">
<?php if ($page == 1){?>
<li class="disabled"><a href="#"><b>&lt;&lt;&nbsp;Prev</b></a></li>
<?php }else{?>
<li><a href="?tab=<?=$tab?>&s=<?=$sections?>&page=<?=$page-1?>"><b>&lt;&lt;&nbsp;Prev</b></a></li>
<?php }?>
<li style="width:30px" class="disabled"><a href="#"><b>Page <?=$page?></b></a></li>
<li><a href="?tab=<?=$tab?>&s=<?=$sections?>&page=<?=$page+1?>"><b>Next&nbsp;&gt;&gt;</b></a></li>
</ul><p></p>
</center>
</div>
</div>
</div>


<script src="./res/marked/marked.min.js"></script>
<script src="./res/js/jquery.taboverride.min.js"></script>
<script src="./res/js/standard.js?v=<?=$SCRIPT_VERSION?>"></script>

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="<?=$MATHJAX_URL?>"></script>


<?php require("footer.php");?>