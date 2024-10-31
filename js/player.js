jQuery(document).ready(function ($) {

	
	// Local copy of jQuery selectors, for performance.
	var	my_jPlayer = $("#jquery_jplayer"),
		my_trackName = $("#jp_container .track-name"),
		my_playState = $("#jp_container .play-state"),
		my_extraPlayInfo = $("#jp_container .extra-play-info");
		
		// Instance jPlayer
	my_jPlayer.jPlayer({
	  // ready: function (event) {
	//		$(this).jPlayer("setMedia", {
	//			mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3"
	//		}).jPlayer("play");
	//	},
		 ready: function (event) {
			if(autoplay==1){
				$(this).jPlayer("setMedia", {
					mp3:src
				}).jPlayer("play");
			}
			else
			{
				$(this).jPlayer("setMedia", {
					mp3:src
				});
			}
		},
		timeupdate: function(event) {
			//my_extraPlayInfo.text(parseInt(event.jPlayer.status.currentPercentAbsolute, 10) + "%");
		},
		play: function(event) {
		   //	my_playState.text(opt_text_playing);
		},
		pause: function(event) {
			//my_playState.text(opt_text_selected);
		},
		ended: function(event) {
		    if(autorepeat==1){ $(this).jPlayer("play"); }
		},
		swfPath: "js",
		cssSelectorAncestor: "#jp_container",
		supplied: "mp3",
		wmode: "window"
	});

	$("#jp_container .track").change(function(e) {
		my_trackName.text($("option:selected",this).text());
		var ll=$(this).val()+"&p=1";
		my_jPlayer.jPlayer("setMedia", {
			mp3: ll
		});
		
		//if((opt_play_first && first_track) || (opt_auto_play && !first_track)) {
			my_jPlayer.jPlayer("play");
		//}
		first_track = false;
		$(this).blur();
		return false;
	});

});