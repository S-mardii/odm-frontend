<?php
/**
 * Flexible Posts Widget: Old Default widget template
 *
 * @since 1.0.0
 *
 * This is the ORIGINAL default template used by the plugin.
 * There is a new default template (default.php) that will be
 * used by default if no template was specified in a widget.
 */

// Block direct requests
if ( !defined('ABSPATH') )
 die('-1');

echo $before_widget;

if ( !empty($title) )
 echo $before_title . $title . $after_title;

if( $flexible_posts->have_posts() ):
?>
 <div class="announcements-list">
  <ul class="dpe-flexible-posts">
  <?php while( $flexible_posts->have_posts() ) : $flexible_posts->the_post(); global $post; ?>
   <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <a href="<?php echo the_permalink(); ?>">
     <h4><?php the_title(); ?></h4>
    </a>
    <div class="poat-date-source">
        <div class="date">
    		<span class="lsf">&#xE12b;</span> <?php the_date(); ?>
    	</div>
    	<div class="source">
        <?php
        if (function_exists(qtranxf_getLanguage)){
            if (qtranxf_getLanguage() <> "en") $lang = "_". qtranxf_getLanguage(); else $lang = "";
            //Get all languages that is available
            $languages = qtranxf_getSortedLanguages();
            $local_lang =  "_".$languages[1];
        }else $lang ="";
        if (get('author')=="" && get('author'.$lang)==""){
            echo "";
        }else{
            //_e('Source: ');
            echo '&nbsp; <span class="icon-news"></span> ';
            echo trim(get('author'.$lang));
        }
        ?>
        </div>
     </div>
     <div class="post-widget-image">
      <?php
       if( $thumbnail == true ) {
            // If the post has a feature image, show it
            if( has_post_thumbnail() ) {
             echo '<a target="_blank" href="'.get_permalink().'" rel="" >';
             the_post_thumbnail( $thumbsize );
             echo "</a>";
            // Else if the post has a mime type that starts with "image/" then show the image directly.
            } elseif( 'image/' == substr( $post->post_mime_type, 0, 6 ) ) {
                echo '<a target="_blank" href="'.get_permalink().'" rel="" >'.wp_get_attachment_image( $post->ID, $thumbsize ).'</a>';
            }

            //Get Cover image
            if (get('cover')=="" && get('cover'.$local_lang)==""){
                echo "";
            }else{
                if(get('cover'.$lang)!=""){
                    $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover'.$lang,1,1,0).'">';
                    $large_img = get_image('cover'.$lang,1,1,0,null,$img_attr);
                }else{
                    if(get('cover')!=""){
                        $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover',1,1,0).'">';
                        $large_img = get_image('cover',1,1,0,null,$img_attr);
                    }
                    else {
                        $get_img = '<img class="attachment-thumbnail" src="'.get_image('cover'.$local_lang,1,1,0).'">';                                        $large_img = get_image('cover'.$local_lang,1,1,0,null,$img_attr);
                    }
                }
                echo '<a target="_blank" href="'.get_permalink().'" rel="" >'.$get_img.'</a>';
            }
       }
      ?>
     </div>
     <?php the_excerpt(); ?>
   </li>
  <?php endwhile; ?>
   <p style="background:#f4f4f4;text-align:center"><a href="<?php echo  get_bloginfo('url')."/". get_post_type();?>"><?php _e("See all") ?></a></p>
  </ul><!-- .dpe-flexible-posts -->

 </div>
<?php else: // We have no posts ?>
 <div class="dpe-flexible-posts no-posts">
  <p><?php _e( 'No post found', 'flexible-posts-widget' ); ?></p>
 </div>
<?php
endif; // End have_posts()

echo $after_widget;
