<?php

function cacheornot_action() {
	global $cache_cacheornot, $cache_enabled;

	if($cache_cacheornot == '1') {

		if($condition) {	// podmínka, za které se nemá kešovat
			define( 'DONOTCACHEPAGE', 1 );
			$cache_enabled = 0;
		}
	}
}

add_cacheaction( 'cache_init', 'cacheornot_action' );

function wp_supercache_cacheornot_admin() {
	global $cache_cacheornot, $wp_cache_config_file, $valid_nonce;

	$cache_cacheornot = $cache_cacheornot == '' ? '0' : $cache_cacheornot;

	if(isset($_POST['cache_cacheornot']) && $valid_nonce) {
		$cache_cacheornot = (int)$_POST['cache_cacheornot'];
		wp_cache_replace_line('^ *\$cache_cacheornot', "\$cache_cacheornot = '$cache_cacheornot';", $wp_cache_config_file);
		$changed = true;
	} else {
		$changed = false;
	}
	$id = 'cacheornot-section';
	?>
		<fieldset id="<?php echo $id; ?>" class="options">
		<h4><?php _e( 'Cache or not (decide programmatically)', 'wp-super-cache' ); ?></h4>
		<form name="wp_manager" action="" method="post">
		<label><input type="radio" name="cache_cacheornot" value="1" <?php if( $cache_cacheornot ) { echo 'checked="checked" '; } ?>/> <?php _e( 'Enabled', 'wp-super-cache' ); ?></label>
		<label><input type="radio" name="cache_cacheornot" value="0" <?php if( !$cache_cacheornot ) { echo 'checked="checked" '; } ?>/> <?php _e( 'Disabled', 'wp-super-cache' ); ?></label>
		<p><?php _e( 'Enables or disables plugin to stop caching pages when certain programmatical condition is met.', 'wp-super-cache' ); ?></p>
		<?php
		if ($changed) {
			if ( $cache_cacheornot )
				$status = __( "enabled" );
			else
				$status = __( "disabled" );
			echo "<p><strong>" . sprintf( __( "Decide Seznam is now %s", 'wp-super-cache' ), $status ) . "</strong></p>";
		}
	echo '<div class="submit"><input class="button-primary" ' . SUBMITDISABLED . 'type="submit" value="' . __( 'Update', 'wp-super-cache' ) . '" /></div>';
	wp_nonce_field('wp-cache');
	?>
	</form>
	</fieldset>
	<?php

}
add_cacheaction( 'cache_admin_page', 'wp_supercache_cacheornot_admin' );
?>