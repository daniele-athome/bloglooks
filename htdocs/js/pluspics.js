/*

    jQuery plugin to retrieve images from Google+ and display with hover effect.
    Author: Dan Aktinson (www.danatkinson.com)
	Usage:
	-----

	$(document).ready(function() {
		$('#myContainer').plusPics({
			userId: 'your_user_id',
			albumId: 'the_album_id',
			numImages: 3,
			title: 'Latest photos'
		});
	});

*/

(function($){
  $.fn.plusPics = function(options) {
    options = $.extend( {
    	'url' : "http://picasaweb.google.com/data/feed/api/user/"+options.userId+"/albumid/"+options.albumId+"?alt=json",
    	'title' : 'Images'
    }, options);

    return this.each(function() {
    	var cont = $(this);
    	$.getJSON(options.url+'&max-results='+options.numImages, function(data) {
  		  var items = [];
  		  cont.empty();
  		  $.each(data.feed.entry, function(i,item) {
  			url = item.media$group.media$content[0].url;
  			title = item.media$group.media$title.$t;
  			photoId = item.gphoto$id.$t;
  		    items.push('<li class="pluspic-'+i+'"><a title="'+title+'" target="_blank" href="http://plus.google.com/photos/'+
  		            options.userId+'/albums/'+options.albumId+'/'+photoId+'"><span><img src="' +url+ '" alt="'+title+'"/></span></a></li>');
  		  });
  		  $('<ul/>', {
  		    'class': 'pluspics',
  		    html: items.join('')+'<div class="clearfix"></div>'
  		  }).appendTo(cont);
  		  $($(cont).children('.pluspics').children('li').children('a').children('span').children('img')).load(function(){
  			   css = {
  					   "margin-left": '-' + ( parseInt( $(this).width() ) / 2 ) + 'px',
  					   "margin-top": '-' + ( parseInt( $(this).height() ) / 2 ) + 'px'
  					};
  			   $(this).css(css);
  			   $(this).fadeIn(1000, function() {
    				 $(this).parent().hover(function() {
						    $(this).addClass("hover");
						    var img = $(this).children('img');
						    $(this).css({ "z-index": "1000"});
						    $(this).stop().animate({
						        width: img.width(),
						        height: img.height(),
						        left: '-'+(parseInt(img.width()-$(this).width())/2)+'px',
			                    top: '-'+(parseInt(img.height()-$(this).height())/2)+'px'
						      }, 500);
						  },
						  function() {
						    $(this).removeClass("hover");
						    $(this).stop().css({ left: "", top: "", width: "", height: "", "z-index": "0"});
						  });
  			      });
  			})
  		});
    });

  };
})(jQuery);