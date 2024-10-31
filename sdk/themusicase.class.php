<?php
class themusicase{
    public function __construct( $token,$version ){
		$this->token=$token;
        $this->version=$version;
	}

    public function get_wix_tracks($params){
        $show_link=get_option('themusicase_show_link');
        $endpoint="http://api.themusicase.com/".$this->version."/get_wix_tracks.php?access_token=$this->token&link=$show_link";
        $x=wp_remote_request($endpoint);
        return $x['body'];
    }
}
?>