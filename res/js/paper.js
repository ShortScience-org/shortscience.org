


prevText = "";
function updatepreview(){

	//$("#entry").val()
	console.log("check");

	var markdownEl = $('#entrytext')
	
	if (markdownEl == null) return;
	
	var rawtext = markdownEl.val();
	
	if (rawtext == null) return;

	if (prevText == rawtext){
		return; // no change
	}else{
		prevText = rawtext
	}
	
	console.log("ran");
	
	$('.userentry.source').first().text(rawtext);
	
	fixedrawtext = rawtext;
	rawtext.replace(/\$+[^$]*[^\](\_)[^$]*\$+/gi, function(match, p1, p2, p3, offset, string){
		fixed = match.replace(/[\\]?_/g,"\\_");
		fixed = fixed.replace(/\\{/g,"\\\\{");
		fixed = fixed.replace(/\\}/g,"\\\\}");
		fixed = fixed.replace(/[\\]?\*/g,"\\*");
		fixed = fixed.replace(/\\\\/g,"\\\\\\");
		fixedrawtext = fixedrawtext.replace(match, "$"+ fixed + "$");
		//console.log("fixed:" + fixed);
	});
	rawtext = fixedrawtext;
	
	
	//rawtext = $('<div/>').text(rawtext).html();

	rawtext =  processCitations(rawtext);
	
	var html = marked(rawtext);
	$('.userentry.rendered').html(html);

	//renderMarkdown('#entry', 'entrymd');

	//deal with huge images without making the user specify a different one.
	$('.userentry.rendered img').map(function(){
		$(this).css("max-width","80%");
		
		$(this).wrap($('<a>',{
			target:"_blank",
			href: this.src
		}));
		
		$(this).wrap($('<center>'));
	});
	
	// style the tables
	$('.rendered table').addClass("table");
	

	if (typeof MathJax !== 'undefined')
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	

}


$(document).ready(function() {

	$(updatepreview);
	//$("#entrytext").tabOverride();
	$("#entrytext").keyup(updatepreview);

	
	$("#yourentry").click(function(){
		$("#entrypreview").show();
		updatepreview();
	});


	$("#submitentry").click(function(){
		console.log("submit");

		$("#submitentry").text("Processing...");
		$("#submitentry").removeClass("btn-default");
		$("#submitentry").addClass("btn-info");
		
		$("#errorbox").html('');
		$.ajax({
			  url: "vignette",
			  type: "POST",
			  data: 
				  {
				  paperid : $("#paperid").val(),
				  text : $("#entrytext").val(),
				  priv : $("#entrypriv").is(':checked')?1:0,
				  anon : $("#entryanon").is(':checked')?1:0,
				  },
			  dataType: "json"
			})
		.done(function(data) {
			location.reload(false);
		    //alert( "success" + data );
		})
		.fail(function(xhr, status, error) {
			$("#errorbox").html("<p><div class='alert alert-danger'>Error (" + xhr.status+ ", " + error + "): " + xhr.responseText + " </div></p>");
			//alert( "error" + xhr.responseText );
			$("#submitentry").text("Submit");
			$("#submitentry").removeClass("btn-info");
			$("#submitentry").addClass("btn-default");
		})
		.always(function() {
	    //alert( "complete" );
		});
	});

	$("#deleteentry").click(function(){
		console.log("delete");

		var r = confirm("Are you sure you want to delete?");
		if (r == true) {
			$("#errorbox").html('');
			$.ajax({
				  url: "./vignette",
				  type: "DELETE",
				  data: 
					  {
					  paperid : $("#paperid").val(),
					  userid : $("#userid").val()
					  },
				  dataType: "json"
				})
			.done(function(data) {
				location.reload(false);
			    //alert( "success" + data );
			})
			.fail(function(xhr, status, error) {
				$("#errorbox").html("<p><div class='alert alert-danger'>Error (" + xhr.status+ ", " + error + "): " + xhr.responseText + " </div></p>");
				//alert( "error" + xhr.responseText );
			})
			.always(function() {
		    //alert( "complete" );
			});
		} else {
		    x = "You pressed Cancel!";
		}
		
	});

//	var hash = document.location.hash
//	
//	if (hash != ""){
//		//console.log("test" + hash);
////		$(".vignette").css('opacity', 0.3);
////		$(".vignette-preview").css('opacity', 1);
////		
////		$(hash).css('opacity', 1);
//		
//		$(hash.replace("comments","")).css('opacity', 1);
//	}
		
	
//	$(".vignette").click(function(){
//		
//		if(history.pushState) {
//		    history.replaceState(null, null, "#" + $(this).attr("id"));
//		}
//	});
	
//	$(".vignette").mouseenter(function(e){
//		
//		$(".vignette").css('opacity', 0.3);
//		$(".vignette-preview").css('opacity', 1);
//		
//		$(this).css('opacity', 1);
//	});
	
});




$(document).ready(function() {
	
	$(".commententry").tabOverride();
	$(".newcomment").keyup(updatecommentpreview);
	
	$(".newcomment").each(function(){
		
		var textbox = $(this).find(".commententry")
		var paperid = $(this).find(".paperid").val();
		var summaryuserid = $(this).find(".summaryuserid").val();
		var submitbtn = $(this).find(".newcommentsubmit");
		
		submitbtn.click(function(){
			
			var text = textbox.val();
			//console.log(text);
			
			console.log("submit");

			submitbtn.text("...");
			submitbtn.removeClass("btn-default");
			submitbtn.addClass("btn-info");
			
			$("#errorbox").html('');
			$.ajax({
				  url: "comment",
				  type: "POST",
				  data: 
					  {
					  paperid : paperid,
					  summaryuserid : summaryuserid,
					  text : text,
					  },
				  dataType: "json"
				})
			.done(function(data) {
				document.location="";
				location.reload(false);
			    //alert( "success" + data );
			})
			.fail(function(xhr, status, error) {
				$("#errorbox").html("<p><div class='alert alert-danger'>Error (" + xhr.status+ ", " + error + "): " + xhr.responseText + " </div></p>");
				
				submitbtn.html('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>');
				submitbtn.removeClass("btn-info");
				submitbtn.addClass("btn-default");
			})
			.always(function() {

			});
		});
	});
	
	$(".comment").each(function(){
		
		var thiscomment = $(this)
		var commentid = $(this).find(".commentid").val();
		var delcommentbtn = $(this).find(".delcommentbtn");
		
		delcommentbtn.click(function(){
			
			console.log("submit");
			
			var r = confirm("Are you sure you want to delete this comment?");
			if (r == true) {
			
				$("#errorbox").html('');
				$.ajax({
					  url: "comment",
					  type: "DELETE",
					  data: 
						  {
						  commentid : commentid,
						  },
					  dataType: "json"
					})
				.done(function(data) {
					//document.location="";
					//location.reload(false);
					
					thiscomment.hide();
				    //alert( "success" + data );
				})
				.fail(function(xhr, status, error) {
					$("#errorbox").html("<p><div class='alert alert-danger'>Error (" + xhr.status+ ", " + error + "): " + xhr.responseText + " </div></p>");
	
				})
				.always(function() {
	
				});
			}
		});
	});
	
});



function updatecommentpreview(d){
	
	
	
	commententry = $(this).find(".commententry");
	text = commententry.val(); 
	comment = $(this).find(".comment");
	display = $(this).find(".comment-text");
	
	if (text == "") comment.hide();
	else comment.show();
	
	display.text(text);
	//console.log(d);
	
	if (typeof MathJax !== 'undefined')
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
}




///// image business

function getBase64(file, callback) {
	var reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = function() {
		if (callback)
			console.log(callback(reader.result.split(',')[1]));
	};
	reader.onerror = function(error) {
		alert("Image upload error: " + 'Error: ' + error);
		console.log('Error: ', error);
		if (callback)
			callback("");
	};
}

function uploadImageCore(file, callback) {
	
	console.log(file);

	/* Is the file an image? */
	if (!file || !file.type.match(/image.*/)){
		alert("File is not an image. Must be jpg, png, or bmp")
		callback("");
		return;
	}
	
	getBase64(file, function(dataBase64) {

		clientId = 'a39a4b064281566';
		auth = 'Client-ID ' + clientId;

		console.log("Image upload started");

		$.ajax({
			url : 'https://api.imgur.com/3/image',
			type : 'POST',
			headers : {
				Authorization : auth,
				Accept : 'application/json'
			},
			data : {
				image : dataBase64,
				type : 'base64'
			},
			success : function(result) {
				var id = result.data.id;
				
				ext = file.name.split('.').pop().toLowerCase();

				imgUrl = "https://i.imgur.com/" + id + "." + ext
				console.log("Uploaded:" + imgUrl);

				if (callback)
					callback(imgUrl);
			},
			error : function(jqXHR, textStatus, errorThrown){
				alert("Image upload error: " + 'Error: ' + textStatus + " " + errorThrown + " Copy your summary into the clipboard in case there is an error saving after this.")
				if (callback)
					callback("");
			}
		});

	});
}


function uploadImage(file, target) {

	$("#imgUploadBtn").text("Uploading...");
	uploadImageCore(file, function(link) {

		var $txt = $(target);
		var caretPos = $txt[0].selectionStart;
		var textAreaTxt = $txt.val();
		var txtToAdd = link;
		$txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));

		$("#imgUploadBtn").text("Add Image");
		updatepreview();
	});
}



