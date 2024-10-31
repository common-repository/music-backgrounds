<?php
include('sdk/themusicase.class.php');
if (!current_user_can('update_plugins'))die();
?>

<style type="text/css">
.nav li{
    padding-top:3px;
}
.iconp-cw:after {  bottom: 1px;}
</style>
<script type="text/javascript">
//<![CDATA[

jQuery(document).ready(function ($) {

            var consoleTimeout;
            $('.minicolors').each( function() {
               // $(this).minicolors();
                $(this).minicolors({
                    change: function(hex, opacity) {
                        var currentId = $(this).attr('id');
                        if(currentId=="background"){
                            var p = $(".nav a").css("background-color", hex);
                        }
                        if(currentId=="font"){
                            var p = $(".nav a").css("color", hex);
                        }
                        if((currentId=="grandientUp")||(currentId=="grandientDown")){
                            var up=$("#grandientUp").val();
                            var down=$("#grandientDown").val();
                            $(".nav").css("background-color",down);
                          $(".nav").css("background", "-webkit-gradient(linear, left top, left bottom, from("+up+"), to("+down+")");
                          $(".nav").css("background", "-webkit-linear-gradient(top, "+up+", "+down+")");
                          $(".nav").css("background", "-moz-linear-gradient(top, "+up+", "+down+")");
                          $(".nav").css("filter", "progid:DXImageTransform.Microsoft.gradient(startColorstr='"+up+"', endColorstr='"+down+"')");
                        }
                    }
                });
            });
});
</script>
<?php

if($_POST['oscimp_hidden'] == 'Y') {
    //Form data sent
    $my_api_key=$_POST['api_key'];
    update_option('themusicase_api_key',$my_api_key);

    $t_show_link=0;
    if(isset($_POST['themusicase_show_link'])) $t_show_link=$_POST['themusicase_show_link'];
    update_option('themusicase_show_link',$t_show_link);


    $track=$_POST['themusicase_track'];
    update_option('themusicase_track', $track);


    $not_footer=0;
    if(isset($_POST['themusicase_not_footer']))    $not_footer=$_POST['themusicase_not_footer'];
    update_option('themusicase_not_footer', $not_footer);

    $all_pages=0;
    if(isset($_POST['themusicase_all_pages']))    $all_pages=$_POST['themusicase_all_pages'];
    update_option('themusicase_all_pages', $all_pages);

    $autoplay=0;
    if(isset($_POST['themusicase_autoplay']))    $autoplay=$_POST['themusicase_autoplay'];
    update_option('themusicase_autoplay', $autoplay);

    $autorepeat=0;
    if(isset($_POST['themusicase_autorepeat']))  $autorepeat=$_POST['themusicase_autorepeat'];
    update_option('themusicase_autorepeat', $autorepeat);

    $fontColor = $_POST['themusicase_fontColor'];
	update_option('themusicase_fontColor', $fontColor);

	$bgroundColor = $_POST['themusicase_bgroundColor'];
	update_option('themusicase_bgroundColor', $bgroundColor);

	$grandientUp = $_POST['themusicase_grandientUp'];
	update_option('themusicase_grandientUp', $grandientUp);

	$grandientDown = $_POST['themusicase_grandientDown'];
	update_option('themusicase_grandientDown', $grandientDown);

?>
	<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
} else {
			//Normal page display
            $my_api_key=get_option('themusicase_api_key');
            $t_show_link=get_option('themusicase_show_link');
            $fontColor = get_option('themusicase_fontColor');
			$bgroundColor = get_option('themusicase_bgroundColor');
			$grandientUp = get_option('themusicase_grandientUp');
			$grandientDown = get_option('themusicase_grandientDown');
            $autoplay=get_option('themusicase_autoplay');
            $autorepeat=get_option('themusicase_autorepeat');
            $all_pages=get_option('themusicase_all_pages');
            $not_footer=get_option('themusicase_not_footer');
            $track=get_option('themusicase_track');

		}

$style="style='color: $fontColor; background-color: $bgroundColor;'";
            $style2="style='background: -webkit-gradient(linear, left top, left bottom, from($grandientUp), to($grandientDown));
background: -webkit-linear-gradient(top, $grandientUp,$grandientDown);
background-color:$grandientDown;
background: -moz-linear-gradient(top, $grandientUp, $grandientDown);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=$grandientUp, endColorstr=$grandientDown);'";
            if($grandientUp==""){
                $fontColor="#a7a7a7";
                $bgroundColor="#f7f7f7";
                $grandientUp="#C43F74";
                $grandientDown="#9E2E5B";
                $style="";
                $style2="";
            }

$version="v1";
$token=$my_api_key;
$paramateres = array();
$api=new themusicase($token,$version);
$x=$api->get_wix_tracks($paramateres);
unset($api);
$x=json_decode($x);
?>
<script>
 var autoplay=0;
 var autorepeat=0;
 var src="<?php echo $track;?>"
</script>
<div class="wrap">
			<?php    echo "<h2>" . __( 'Background Music Options', 'oscimp_trdom' ) . "</h2>"; ?>

			<form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                <div id="jquery_jplayer"></div>
                 <div id="jp_container">
                <input type="hidden" name="oscimp_hidden" value="Y">
               	<?php    echo "<h3>" . __( 'API Settings', 'oscimp_trdom' ) . "</h3>"; ?>
                <p>
                   Api Key: <input type="text" name="api_key" value="<?php echo $my_api_key;?>" style="width:300px"> Don't have API KEY? <a target="_blank" href="http://api.themusicase.com/wordpress/authorize.php?response_type=code&client_id=Wordpress&host=<?php echo $_SERVER['HTTP_HOST'];?>">Get one here.</a>
                </p>
    		    <hr/>
                <?php    echo "<h3>" . __( 'Choose a track', 'oscimp_trdom' ) . "</h3>"; ?>
				<p><?php _e("Available Tracks: " ); ?>
                    <select class="track" name="themusicase_track">
                        <option>Select a track:</option>
                        <?php
                        if(isset($x->tracks)){
                        foreach ( $x->tracks as $ctrack )
                          {
                            $m_id=$ctrack->id;
                            $m_source="http://api.themusicase.com/v1/get_wix_source.php?id=$m_id&access_token=$token";
                            ?><option value="<?php echo $m_source;?>" <?php if($track==$m_source) echo "selected";?>><?php echo $ctrack->title;?></option>
                          <?php
                          }
                          }
                        ?>
                    </select> (You need a valid Api Key to view and select tracks)
                </p>
                               <hr/>
                 <?php    echo "<h3>" . __( 'Extra feature: Access to 450 more royalty free music tracks', 'oscimp_trdom' ) . "</h3>"; ?>
                 <p><b>Show</b> <i>music provided by themusicase.com link</i>&nbsp;<input type="checkbox" name="themusicase_show_link" value="1" <?php if($t_show_link==1) echo "checked";?>>&nbsp;&nbsp;&nbsp;(Check and click update options to unlock all our royalty free music tracks)</p>

                <hr/>
                <?php    echo "<h3>" . __( 'Player Options', 'oscimp_trdom' ) . "</h3>"; ?>
                <p><?php _e("Autoplay: " ); ?><input type="checkbox" name="themusicase_autoplay" value="1" <?php if($autoplay==1) echo "checked";?>></p>
				<p><?php _e("Autorepeat: " ); ?><input type="checkbox" name="themusicase_autorepeat" value="1" <?php if($autorepeat==1) echo "checked";?>></p>
                <p><?php _e("Show on all pages: " ); ?><input type="checkbox" name="themusicase_all_pages" value="1" <?php if($all_pages==1) echo "checked";?>></p>
                <p><?php _e("Do not show on footer: " ); ?><input type="checkbox" name="themusicase_not_footer" value="1" <?php if($not_footer==1) echo "checked";?>>&nbsp;&nbsp;&nbsp;Code inside Theme Loop: <code>&lt;?php if(function_exists('themusicase_player')) themusicase_player(); ?></code></p>
				<hr />
				<?php    echo "<h3>" . __( 'Customize Player', 'oscimp_trdom' ) . "</h3>"; ?>
				 <p>
                    <b>Gradient Up Color</b>
                    <input class="minicolors" name="themusicase_grandientUp" id="grandientUp" data-theme="bootstrap" type="text" value="<?php echo $grandientUp;?>">
                    <br><br><br>
                    <b>Gradient Down Color</b>
                    <input class="minicolors" name="themusicase_grandientDown" id="grandientDown" data-theme="bootstrap" type="text" value="<?php echo $grandientDown;?>">
                    <br><br><br>
                    <b>Background Color</b>
                    <input class="minicolors" name="themusicase_bgroundColor" id="background" data-theme="bootstrap" type="text" value="<?php echo $bgroundColor;?>">
                    <br><br><br>
                     <b>Font Color</b>
                    <input class="minicolors" name="themusicase_fontColor" id="font" data-theme="bootstrap" type="text" value="<?php echo $fontColor;?>">
                    <br><br>
                </p>
                <p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom' ) ?>" />
				</p>

                <div id="player">
    			<ul class="nav" <?php echo $style2;?>>
                    <li>
                        <a href="javascript:void(0);" class="iconp-home jp-play" <?php echo $style;?>></a>
                        <a href="javascript:void(0);" class="iconp-cog jp-pause" <?php echo $style;?>></a>
                    </li>
                    <li><a href="javascript:void(0);" class="iconp-cw jp-stop" <?php echo $style;?>></a></li>
    			</ul>
                    <!--<div class="powered">By Themusicase.com</div>!-->
                </div>
                </div>

			</form>
		</div>
