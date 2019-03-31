<?php require("header.php");?>
<?php require("menu.php");?>
<?//print_r($results);die();?>
<div class="container">
<div class="col-md-1"></div>
<div class="col-md-10">
<br>

<style>
.cse .gsc-search-button input.gsc-search-button-v2, input.gsc-search-button-v2 {
    width: 71px; !important;
    height: 29px; !important;
}

table.gsc-search-box td {
    vertical-align: top;
}

.gsc-zippy {
	display:none;
}

.gsc-url-top {
	display:none;
}

.gsc-table-result, .gsc-thumbnail-inside, .gsc-url-top {
    padding-left: 0px;
}

</style>

<a href="/search?term=<?=htmlspecialchars($q)?>">
<center><div class="alert alert-info">Cannot find a paper? Click here to add it!</div></center>
</a>


<script>
  (function() {
    var cx = '009958962865329865701:0pd-b8qugwy';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search enableAutoComplete="true"></gcse:search>


</div>
</div>
<br><br><br>

<?php require("footer.php");?>