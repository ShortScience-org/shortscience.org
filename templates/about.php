<?php require("header.php");?>
<?php require("menu.php");?>

<br>
<div class="container" style="max-width:750px; margin:0 auto; font-size: 1.2em;">

<div class="source">
# Welcome to ShortScience.org!

Research papers can be hard to understand for anyone entering a field of research. 
It is useful to ask an expert in the field to summarize the intuition behind a paper to help you understand it but they can be hard to find. 
ShortScience.org allows researchers to publish paper summaries that are voted on and ranked until the best and most accessible summary has been found. 
We feel that the site can speed up the literature review process and increase the number of active researchers by decreasing the barriers to understand and improve on concepts. 


Everyone can write summaries for any paper that exists in our database (which includes anything with a DOI, on arXiv, or on Bibsonomy). 
These summaries are voted on by each user using a simple up or down metric. 
Each summary can be set as private which is useful for personal organization of papers. 

*"If I can't explain it simply, I don't understand it well enough."*

ShortScience.org is inspired by this Einstein quote.
Do you understand a paper well enough? 
Prove it to yourself by writing a summary for it! 
Also, write summaries of your own papers to help people to understand it and gain impact!

## Tips to use the service:

1. Search for papers using the full title, the DOI, or ar$\large\chi$iv id. The search can access all papers in the [DOI](http://www.crossref.org/), [ar$\large\chi$iv](http://arxiv.org), [dblp](http://dblp.uni-trier.de/), and [Bibsonomy](http://bibsonomy.org) databases to allow you to write a summary for just about all research papers.
2. When you write a summary you can choose to keep it private or make it public. You can also make it public and hide your name.
3. You can look at all your summaries on your [profile page](user) to quickly review each paper.
4. You can view the most recent summaries on the home screen once you are logged in!
5. When writing your summary you can write $\LaTeX$ and see real-time rendering of the equations. You can also use [markdown syntax](https://en.support.wordpress.com/markdown-quick-reference/) to make things bold or insert tables or images or code.
6. To see the source of of someone's summary click the eye icon.
7. Papers are identified by *bibtexKey*s which can will be a Bibsonomy key or a DOI. In your summary you can reference other papers with these keys in a $\LaTeX$ \cite reference.
8. To get started [create an account](signup). If you don't like it you can export all your summaries from your [profile page](user). 
9. Once you are finished writing your post click the share arrow to post it on your social media.
10. Subscribe to our [RSS](http://www.shortscience.org/rss.xml) feed to stay up to date.

## Contact Us:
</div>

<div class="rendered">
</div>

<br>
<center>
Primary contact: Joseph Paul Cohen <script type="text/javascript"> document.write('<a href="mailto:joseph@' + 'josephpcohen.com">joseph@' + 'josephpcohen.com</a>')</script>
</center>
<br>

    <div class="row">
	    <div class="col-sm-6">
	    
	    <img class="col-sm-12" style="width:100%"  src="res/joe.jpg"/>
	    
	    <center>
	    Founder<br>
	    Joseph Paul Cohen PhD<br><script type="text/javascript"> document.write('<a href="mailto:joecohen@' + 'cs.umb.edu">joecohen@' + 'cs.umb.edu</a>')</script><br>
	    National Science Foundation Graduate Fellow<br>
	    Computer Science Department<br>
	    University of Massachusetts Boston
	    </center>
	    
	    </div>
	    
	    <div class="col-sm-6">
	    
	    
	    <img class="col-sm-12" style="width:100%" src="res/siyer.jpg"/>
	   
	    <center>
	    Co-founder<br>
	    Swami Iyer PhD<br><script type="text/javascript"> document.write('<a href="mailto:swamir@' + 'cs.umb.edu">swamir@' + 'cs.umb.edu</a>')</script><br>
	    Lecturer, Computer Science Department<br>
	    University of Massachusetts Boston
	    </center>
	    
	    </div>
	    
	    
	</div>

<h2>Service Stats:</h2>

	<table class="table">
	<?php 
	$stats = getStats();
	
	for ($i = 0; $i < sizeof($stats); $i++) {
	?>
	
	<tr>
	<td><?=$stats[$i][0]?></td>
	<td><?=$stats[$i][1]?></td>
	</tr>
	
	<?php }?>
	</table>


</div>

<script src="./res/marked/marked.min.js"></script>
<script src="./res/js/jquery.taboverride.min.js"></script>
<script src="./res/js/standard.js"></script>

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>


<?php //require("footer.php");?>