<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="origincode_vdg_add_videos" style="display: none;">
    <div id="origincode_vdg_add_videos">
        <div id="origincode_vdg_add_videos_wrap" data-add-video-nonce="" data-videgallery-id="">
            <h2><?php _e('Add Video URL From Youtube or Vimeo', 'origincode-vdg');?></h2>
            <div class="control-panel">
                <form method="post" action="" >
                    <input type="text" id="origincode_add_video_input" name="origincode_add_video_input" placeholder="https://" />
                    <button class='save-slider-options button-primary origincode-insert-video-button' id='origincode-insert-video-button'> <?php _e('Insert Video', 'origincode-vdg');?></button>
                    <div id="add-video-popup-options">
                        <div>
                            <div>
                                <label for="show_title"><?php _e('Title', 'origincode-vdg');?>:</label>
                                <div>
                                    <input name="show_title" value="" type="text" />
                                </div>
                            </div>
                            <div>
                                <label for="show_description"><?php _e('Description', 'origincode-vdg');?>:</label>
                                <textarea id="show_description" name="show_description"></textarea>
                            </div>
                            <div>
                                <label for="show_url"> <?php _e('Url', 'origincode-vdg');?> :</label>
                                <input type="text" name="show_url" value="" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
