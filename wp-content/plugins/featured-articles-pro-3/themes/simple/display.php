<?php 
	// get the options implemented by the slider theme
	$options = get_slider_theme_options();
?>
<div class="<?php the_slider_class( 'fa_slider_simple' );?>" style="<?php the_slider_styles();?>" id="<?php the_slider_id();?>" <?php the_slider_data();?>>
	<?php 
		// loop slides in slider
		while( have_slides() ): 
	?>
	<div class="fa_slide <?php the_fa_class();?>" style="<?php the_slide_styles();?>">		
		<div class="fa_slide_content">	
			<?php 
				// display the slide title wrapped in h2 tag
				the_fa_title('<h2>', '</h2>');
			?>
			<?php 
				// display the slide content wrapped in div
				the_fa_content('<div class="description">', '</div>');
			?>
			<?php 
				// display read more link
				the_fa_read_more('fa_read_more');
			?>
			<?php 
				// display play video link
				the_fa_play_video('fa_play_video', 'modal');
			?>
		</div><!-- .fa_slide_content -->
		
		<?php 
			// show the slide image wrapped in a div
			the_fa_image( '<div class="fa_image">', '</div>', false );
		?>			
	</div><!-- .fa_slide -->
	<?php 
		// end loop
		endwhile;
	?>
	<?php 
		// show sideways navigation if enabled
		if( has_sideways_nav() ):
	?>
		<div class="go-forward"></div>
		<div class="go-back"></div>
	<?php endif;?>
	<?php 
		// show dots navigation if enabled
		if( has_bottom_nav() ):
	?>
		<div class="main-nav">
			<?php while( have_slides() ):?>
			<a href="#" title="" class="fa-nav"></a>
			<?php endwhile;?>		
		</div>
	<?php endif;?>
	<?php 
		// show progress bar according to user settings
		if( isset( $options['show_timer'] ) && $options['show_timer'] ):
	?>	
	<div class="progress-bar"><!-- slider progress bar --></div>
	<?php endif;// show timer?>
</div>