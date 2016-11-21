<?php 
//given $shareurl
//and $sharetext
//and $shareanchor

?>
<style>
.share-buttons img {
height:40px;
}
</style>
<div class="share-buttons" id="<?=$shareanchor?>" style="text-align: center;display:none;margin:10px">  

    <a href="<?=$shareurl?>" title="Get a direct link to this summary">
        <img src="/res/img/sharebuttons/urli.png" alt="URL" /></a>

    <!-- Twitter -->
    <a href="https://twitter.com/share?url=<?=urlencode($shareurl)?>&amp;text=<?=urlencode($sharetext)?>&amp;hashtags=shortscience" target="_blank">
        <img src="/res/img/sharebuttons/simple/twitter.png" alt="Twitter" /></a>
    
    <!-- Facebook -->
    <a href="http://www.facebook.com/sharer.php?u=<?=urlencode($shareurl)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/facebook.png" alt="Facebook" /></a>

    <!-- Buffer -->
    <a href="https://bufferapp.com/add?url=<?=$shareurl?>&amp;text=<?=urlencode($sharetext)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/buffer.png" alt="Buffer" /></a>
    
    <!-- Digg -->
    <a href="http://www.digg.com/submit?url=<?=urlencode($shareurl)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/diggit.png" alt="Digg" /></a>
    
    <!-- Email -->
    <a href="mailto:?Subject=<?=urlencode($sharetext)?>&amp;Body=<?=$sharetext?> <?=$shareurl?>">
    <img src="/res/img/sharebuttons/simple/email.png" alt="Email" /></a>
    
    <!-- Google+ -->
    <a href="https://plus.google.com/share?url=<?=$shareurl?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/google.png" alt="Google" /></a>
    
    <!-- LinkedIn -->
    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?=$shareurl?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/linkedin.png" alt="LinkedIn" /></a>
    
    <!-- Pinterest -->
    <a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
        <img src="/res/img/sharebuttons/simple/pinterest.png" alt="Pinterest" /></a>
    
    <!-- Print 
    <a href="javascript:;" onclick="window.print()">
        <img src="/res/img/sharebuttons/simple/print.png" alt="Print" /></a>
    -->
    
    <!-- Reddit -->
    <a href="http://reddit.com/submit?url=<?=$shareurl?>&amp;title=<?=urlencode($sharetext)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/reddit.png" alt="Reddit" /></a>
    
    <!-- StumbleUpon-->
    <a href="http://www.stumbleupon.com/submit?url=<?=$shareurl?>&amp;title=<?=urlencode($sharetext)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/stumbleupon.png" alt="StumbleUpon" /></a>
    
    <!-- Tumblr-->
    <a href="http://www.tumblr.com/share/link?url=<?=$shareurl?>&amp;title=<?=urlencode($sharetext)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/tumblr.png" alt="Tumblr" /></a>
    
    <!-- VK -->
    <a href="http://vkontakte.ru/share.php?url=<?=$shareurl?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/vk.png" alt="VK" /></a>
    
    
    <!-- Yummly 
    <a href="http://www.yummly.com/urb/verify?url=<?=$shareurl?>&amp;title=<?=urlencode($sharetext)?>" target="_blank">
        <img src="/res/img/sharebuttons/simple/yummly.png" alt="Yummly" /></a>
-->
</div>