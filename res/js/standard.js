
function processCitations(input){

	var toReturn = input;
	
	var regex = /\\cite{[a-zA-Z0-9_.():\-\\\/]*}/g;

	 while (match = regex.exec(input)) {
	  	console.log(match[0]);

	  	var citation = match[0].substring(6,match[0].length-1);
	  	
	  	toReturn = toReturn.replace(match[0], "[[" + citation + "]](/paper?bibtexKey=" + citation + ")");
	    //params[decode(match[1])] = decode(match[2]);
	  }

	return toReturn;
}


//$("img").one("load", function(i) {
//	more_less_btns();
//	console.log("Asdasda" + i)
//	}).each(function() {
//	  if(this.complete) $(this).load();
//	});


//$(function() {
//    function imageLoaded() {
//    	more_less_btns();
//    }
//    $('img').each(function() {
//        if( this.complete ) {
//            imageLoaded.call( this );
//        } else {
//            $(this).one('load', imageLoaded);
//        }
//    });
//});

function isOverflowed(element){
    return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
}

function more_less_btns(){

	// enable more/less on summaries
	$(".panel").each(function(i,e){
		
		var rendered = $(e).find(".rendered");
		var more = $(e).find(".more");
		var less = $(e).find(".less");
		
		
		// test if there is no need for buttns
		if (isOverflowed(rendered[0])){
		
			$(more).show();
			$(more).click(function(ee){
				$(rendered).css("max-height","10000px");
				$(more).hide();
				$(less).show();
			});
			
			$(less).click(function(ee){
				$(rendered).css("max-height","400px");
				$(less).hide();
				$(more).show();
			});
		}
	});
}


$(document).ready(function() {
	$(".source").each(function(e, markdownEl){
		//console.log(g);

		$(markdownEl).hide();
		
		var rawtext = markdownEl.textContent;
		
		if (markdownEl == null || rawtext == null) return;
		
		
		// kind of a hack but it is ok
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
		
		rendered = $(markdownEl).parent().find(".rendered");
		
		rendered.html(html);
		
		rendered.show();
	});

	//deal with huge images without making the user specify a different one.
	$('.rendered img').map(function(){
		$(this).css("max-width","80%");
		
		$(this).wrap($('<a>',{
			target:"_blank",
			href: this.src
		}));
		
		$(this).wrap($('<center>'));
		
		// on image load
		$(this).one("load", function(i) {
			
			more_less_btns();
			
			}).each(function() {
				// if cached
				if(this.complete) $(this).load();
			});
	});
	
	more_less_btns();
	
	$('.rendered table').addClass("table");

	
	$(".viewsource").click(function(obj){
		
		vignette = $(this).closest(".vignette").first();
		source = vignette.find(".source")
		rendered = vignette.find(".rendered")
		
		if (source.is(":visible")){
			source.hide();
			rendered.show();
		}else{
			source.show();
			rendered.hide();
		}
	});
	
	
	$(".votebtn").click(function(obj){
		
		// need to use this.parentElement directly for IE11
		//parent = $(this).parents(".voteblock").first();
		console.log($(this.parentElement).attr("class"));
		up = $(this.parentElement).find(".votebtn-up").first();
		down = $(this.parentElement).find(".votebtn-down").first();
		votevalue = $(this.parentElement).find(".votevalue").first();
		myvote = $(this.parentElement).find(".myvote").first();
		
		paperid = $(this.parentElement).find(".paperid").first().val();
		userid = $(this.parentElement).find(".userid").first().val();
		voteruserid = $(this.parentElement).find(".voteruserid").first().val();
		vote = parseInt(myvote.val());
		totalvote = parseInt(votevalue.html());
		
		console.log("votebtn");
		
		if (voteruserid == -1){
			window.location = "./login?returnto=./paper?bibtexKey=" + paperid;
			return;
		}
		
		
		console.log("myvote: " + vote+ ", votevalue: " + totalvote);


		
		changed = false;
		if (this==up[0] && (vote  == 0 || vote  == -1)){
			// click was up
			
			myvote.val(vote+1);
			votevalue.text(totalvote+1);
			changed = true;
			
		}else if (this==down[0] && (vote == 1 || vote == 0)){
			// click was down
		
			myvote.val(vote-1);
			votevalue.text(totalvote-1);
			changed = true;
		}

		
		if (changed == true){

			console.log("changed");
			vote = parseInt(myvote.val());
	 		$("#errorbox").html('');
	 		$.ajax({
	 			  url: "vote",
	 			  type: "POST",
	 			  data: 
	 				  {
	 				  paperid : paperid,
	 				  userid : userid,
	 				  vote : vote,
	 				  },
	 			  dataType: "json"
	 			})
	 		.done(function(data) {
	 			//location.reload(false);
	 		    //alert( "success" + data );
	 		})
	 		.fail(function(xhr, status, error) {
	 			$("#errorbox").html("<p><div class='alert alert-danger'>Error (" + xhr.status+ ", " + error + "): " + xhr.responseText + " </div></p>");
	 			//alert( "error" + xhr.responseText );
	 		})
	 		.always(function() {
	 	    //alert( "complete" );
	 		});


			if (vote == 0){

				up.css('color', 'black');
				down.css('color', 'black');

			}else if (vote == 1){

				up.css('color', 'blue');
				down.css('color', 'black');

				
			}else if (vote == -1){

				up.css('color', 'black');
				down.css('color', 'red');

			}
		}
	});
	
	
	
	
	$("#savesettings").click(function(){
		console.log("savesettings");

		
		$("#errorbox").html('');
		$.ajax({
			  url: "user",
			  type: "POST",
			  data: 
				  {
				  username : $("#username").val(),
				  displayname : $("#displayname").val(),
				  description : $("#description").val(),
				  orcid : $("#orcid").val(),
				  email_receive_comments : $("#email_receive_comments").is(':checked'),
				  password : $("#password").val()
				  },
			  dataType: "json"
			})
		.done(function(data) {
			location.reload(false);
			
			if ($("#password").val() != "")
				document.location = "./login";
			
		    //alert( "success" + data );
		})
		.fail(function(xhr, status, error) {
			$("#errorbox").html("<p><div class='alert alert-danger'>Error (" + xhr.status+ ", " + error + "): " + xhr.responseText + " </div></p>");
			//alert( "error" + xhr.responseText );
		})
		.always(function() {
	    //alert( "complete" );
		});
	});
	
	
});



$(document).ready(function() {
	
	var originalLeave = $.fn.popover.Constructor.prototype.leave;
	$.fn.popover.Constructor.prototype.leave = function(obj){
		  var self = obj instanceof this.constructor ?
		    obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
		  var container, timeout;

		  originalLeave.call(this, obj);

		  if(obj.currentTarget) {
		    container = $(obj.currentTarget).siblings('.popover')
		    timeout = self.timeout;
		    container.one('mouseenter', function(){
		      //We entered the actual popover – call off the dogs
		      clearTimeout(timeout);
		      //Let's monitor popover content instead
		      container.one('mouseleave', function(){
		        $.fn.popover.Constructor.prototype.leave.call(self, self);
		      });
		    })
		  }
		};
	$('.usericonpop').popover({ trigger: "hover click",
								animation:true,
								html:true,
								delay: {show: 50, hide: 400}});
	
	$('.commentusericonpop').popover({ trigger: "hover click",
		animation:true,
		html:true,
		placement:"left",
		delay: {show: 50, hide: 400}});
	
	$('.nodisplaynameiconpop').popover({ trigger: "hover focus",
		animation:true,
		html:true,
		placement:"right",
		delay: {show: 50, hide: 400}});
		
	$('.arxivsanitypop').popover({ trigger: "hover click",
		animation:true,
		html:true,
		placement:"left",
		template: '<div class="popover" role="tooltip" style="max-width: 600px;"><div class="arrow"></div><h3 class="popover-title" style="background-color: #840000;color:white;"></h3><div class="popover-content"></div></div>'});
	
	$('.arxivsanitypop').click(function(){
		$('.arxivsanitypop').popover('hide');
	});
	$('.arxivsanitypop').on("mouseleave",function(){
		$('.arxivsanitypop').popover('hide');
	});
	
	$('.abstractpop').popover({ trigger: "hover click",
		animation:true,
		html:true,
		placement:"bottom",
		delay: {show: 50, hide: 400},
		template: '<div class="popover" role="tooltip" style="max-width: 450px;"><div class="arrow"></div><h3 class="popover-title" style=""></h3><div class="popover-content"></div></div>'
	});
	
});



var renderer = new marked.Renderer();

/// here reduce the size of headings so they are not huge
renderer.heading2 = renderer.heading;
renderer.heading = function(){
	
	arguments[1] = arguments[1]+2
	
	return renderer.heading2.apply(renderer,arguments);
}

/// here render the links that are videos as videos
renderer.link2 = renderer.link;
renderer.link = function(){
	//console.log(arguments);
    href = arguments[0]; 
    
    // if link does not have a title
    if (arguments[0] == arguments[2]){
    	
        var YouTube = mediaParseIdFromUrl('youtube', href);
        var Vimeo = mediaParseIdFromUrl('vimeo', href);
        var Viddler = mediaParseIdFromUrl('viddler', href);
        var DailyMotion = mediaParseIdFromUrl('dailymotion', href);
        var Html5 = mediaParseIdFromUrl('html5', href);
        var Image = mediaParseIdFromUrl('image', href);
        
        if (YouTube !== undefined) {
            out = '<div class="videoWrapper" style="position: relative;padding-bottom: 56.25%; padding-top: 25px;height: 0;"><iframe width="100%" height="280" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="//www.youtube.com/embed/' + YouTube + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
        } else if (Vimeo !== undefined) {
            out = '<div class="videoWrapper" style="position: relative;padding-bottom: 56.25%; padding-top: 25px;height: 0;"><iframe width="100%" height="280" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;"src="//player.vimeo.com/video/' + Vimeo + '?api=1" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
        } else if (Viddler !== undefined) {
            out = '<div class="videoWrapper" style="position: relative;padding-bottom: 56.25%; padding-top: 25px;height: 0;"><iframe width="100%" height="280" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="//www.viddler.com/player/' + Viddler + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
        } else if (DailyMotion !== undefined) {
            out = '<div class="videoWrapper" style="position: relative;padding-bottom: 56.25%; padding-top: 25px;height: 0;"><iframe width="100%" height="280" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="//www.dailymotion.com/embed/video/' + DailyMotion + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
        } else if (Html5 !== undefined) {
        	out = '<center><video src="' + Html5['link'] + '" controls>Your browser does not support the video tag. Video link: <a href="' + Html5['link'] + '">' + Html5['link'] + '</a></video></center>'
        } else if (Image !== undefined) {
            out = '<img src="' + href + '">';
        } else {
        	out = renderer.link2.apply(renderer,arguments);
        }
    } else {
        out = renderer.link2.apply(renderer,arguments);
    }
    return out;
};

marked.setOptions({
	  renderer: renderer,
	  gfm: true,
	  tables: true,
	  breaks: false,
	  pedantic: false,
	  sanitize: true,
	  smartLists: true,
	  smartypants: false
});



/**
 * Parse url of video to return Video ID only
 * if video exists and matches to media's host
 * else undefined
 *
 * @example mediaParseIdFromUrl('youtube', 'https://www.youtube.com/watch?v=fgQRaRqOTr0')
 * //=> fgQRaRqOTr0
 * 
 * @param  {string} provider    name of media/video site
 * @param  {string} url         url of video
 * @return {string|undefined}   the parsed id of video, if not match - undefined
 */
function mediaParseIdFromUrl(provider, url) {
  if (provider === 'youtube') {
    var youtubeRegex = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var youtubeMatch = url.match(youtubeRegex);
    if (youtubeMatch && youtubeMatch[7].length == 11) {
      return youtubeMatch[7];
    } else {
      return undefined;
    }
  } else if (provider === 'vimeo') {
    var vimeoRegex = /^.*vimeo.com\/(\d+)/;
    var vimeoMatch = url.match(vimeoRegex);
    if (vimeoMatch && vimeoMatch[1].length > 5) {
      return vimeoMatch[1];
    } else {
      return undefined;
    }
  } else if (provider === 'viddler') {
    var viddlerRegex = /^.*((viddler.com\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var viddlerMatch = url.match(viddlerRegex);
    if (viddlerMatch && viddlerMatch[7].length == 8) {
      return viddlerMatch[7];
    } else {
      return undefined;
    }
  } else if (provider === 'dailymotion') {
    var dailymotionRegex = /^.+dailymotion.com\/((video|hub)\/([^_]+))?[^#]*(#video=([^_&]+))?/;
    var dailymotionMatch = url.match(dailymotionRegex);
    if (dailymotionMatch && (dailymotionMatch[5] || dailymotionMatch[3])) {
      if (dailymotionMatch[5]) {
        return dailymotionMatch[5];
      }
      if (dailymotionMatch[3]) {
        return dailymotionMatch[3];
      }
      return undefined;
    } else {
      return undefined;
    }
  } else if (provider === 'html5') {
    var html5Regex = /(wav|mp3|ogg|mp4|wma|webm|mp3)$/i;
    var html5Match = url.match(html5Regex);
    if (html5Match) {
    	var data = {
    			extension: html5Match,
                link: url
    	};
    	return data;
	} else {
		return undefined;
	}
  } else if (provider === 'image') {
	    var imageRegex = /(jpg|jpeg|png|gif)$/i;
	    var imageMatch = url.match(imageRegex);
	    if (imageMatch) {
	    	var data = {
	    			extension: imageMatch,
	                link: url
	    	};
	    	return data;
		} else {
			return undefined;
		}
  } else {
    return undefined;
  }
}


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

