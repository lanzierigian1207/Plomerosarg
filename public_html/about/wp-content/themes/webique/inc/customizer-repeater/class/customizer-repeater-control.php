<?php if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class WEBIQUE_Repeater extends WP_Customize_Control {

	public $id;
	private $boxtitle = array();
	private $add_field_label = array();
	private $customizer_repeater_title_control = false;
	private $customizer_repeater_subtitle_control = false;
	private $customizer_repeater_subtitle2_control = false;
	private $customizer_repeater_description_control = false;
	private $customizer_repeater_description2_control = false;
	private $customizer_repeater_text_control = false;
	private $customizer_repeater_text2_control = false;
	private $customizer_repeater_text3_control = false;
	private $customizer_repeater_text4_control = false;
	private $customizer_repeater_text5_control = false;
	private $customizer_repeater_text6_control = false;
	private $customizer_repeater_text7_control = false;
	private $customizer_repeater_link_control = false;
	private $customizer_repeater_link2_control = false;
	private $customizer_repeater_link3_control = false;
	private $customizer_repeater_link4_control = false;
	private $customizer_repeater_link5_control = false;
	private $customizer_repeater_link6_control = false;
	private $customizer_repeater_link7_control = false;
	private $customizer_repeater_button_text_control = false;
	private $customizer_repeater_button_link_control = false;
	private $customizer_repeater_button2_text_control = false;
	private $customizer_repeater_button2_link_control = false;
	private $customizer_repeater_slide_align = false;
	private $customizer_repeater_video_url_control = false;
	private $customizer_repeater_image_control = false;
	private $customizer_repeater_image2_control = false;
	private $customizer_repeater_image3_control = false;
	private $customizer_repeater_image4_control = false;
	private $customizer_repeater_icon_control = false;
	private $customizer_repeater_icon2_control = false;
	private $customizer_repeater_color_control = false;
	private $customizer_repeater_color2_control = false;
	private $customizer_repeater_designation_control = false;
	private $customizer_repeater_shortcode_control = false;
	private $customizer_repeater_repeater_control = false;
	private $customizer_repeater_repeater_img_control = false;
	private $customizer_repeater_checkbox_control = false;
	private $customizer_repeater_checkbox2_control = false;
	private $customizer_repeater_newtab_control = false;
	private $customizer_repeater_nofollow_control = false;
	
    private $customizer_icon_container = '';
    private $allowed_html = array();


	/*Class constructor*/
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new field', 'webique' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle = esc_html__( 'Customizer Repeater', 'webique' );
		if ( ! empty ( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['customizer_repeater_image_control'] ) ) {
			$this->customizer_repeater_image_control = $args['customizer_repeater_image_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_image2_control'] ) ) {
			$this->customizer_repeater_image2_control = $args['customizer_repeater_image2_control'];
		}

		if ( ! empty( $args['customizer_repeater_image3_control'] ) ) {
			$this->customizer_repeater_image3_control = $args['customizer_repeater_image3_control'];
		}

		if ( ! empty( $args['customizer_repeater_image4_control'] ) ) {
			$this->customizer_repeater_image4_control = $args['customizer_repeater_image4_control'];
		}

		if ( ! empty( $args['customizer_repeater_icon_control'] ) ) {
			$this->customizer_repeater_icon_control = $args['customizer_repeater_icon_control'];
		}

		if ( ! empty( $args['customizer_repeater_icon2_control'] ) ) {
			$this->customizer_repeater_icon2_control = $args['customizer_repeater_icon2_control'];
		}

		if ( ! empty( $args['customizer_repeater_color_control'] ) ) {
			$this->customizer_repeater_color_control = $args['customizer_repeater_color_control'];
		}

		if ( ! empty( $args['customizer_repeater_color2_control'] ) ) {
			$this->customizer_repeater_color2_control = $args['customizer_repeater_color2_control'];
		}

		if ( ! empty( $args['customizer_repeater_title_control'] ) ) {
			$this->customizer_repeater_title_control = $args['customizer_repeater_title_control'];
		}
		

		if ( ! empty( $args['customizer_repeater_subtitle_control'] ) ) {
			$this->customizer_repeater_subtitle_control = $args['customizer_repeater_subtitle_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_subtitle2_control'] ) ) {
			$this->customizer_repeater_subtitle2_control = $args['customizer_repeater_subtitle2_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_description_control'] ) ) {
			$this->customizer_repeater_description_control = $args['customizer_repeater_description_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_description2_control'] ) ) {
			$this->customizer_repeater_description2_control = $args['customizer_repeater_description2_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_text_control'] ) ) {
			$this->customizer_repeater_text_control = $args['customizer_repeater_text_control'];
		}
		if ( ! empty( $args['customizer_repeater_text2_control'] ) ) {
            $this->customizer_repeater_text2_control = $args['customizer_repeater_text2_control'];
        }
		if ( ! empty( $args['customizer_repeater_text3_control'] ) ) {
            $this->customizer_repeater_text3_control = $args['customizer_repeater_text3_control'];
        }
		if ( ! empty( $args['customizer_repeater_text4_control'] ) ) {
            $this->customizer_repeater_text4_control = $args['customizer_repeater_text4_control'];
        }
		if ( ! empty( $args['customizer_repeater_text5_control'] ) ) {
            $this->customizer_repeater_text5_control = $args['customizer_repeater_text5_control'];
        }
		if ( ! empty( $args['customizer_repeater_text6_control'] ) ) {
            $this->customizer_repeater_text6_control = $args['customizer_repeater_text6_control'];
        }
		if ( ! empty( $args['customizer_repeater_text7_control'] ) ) {
            $this->customizer_repeater_text7_control = $args['customizer_repeater_text7_control'];
        }
		if ( ! empty( $args['customizer_repeater_link_control'] ) ) {
			$this->customizer_repeater_link_control = $args['customizer_repeater_link_control'];
		}
		if ( ! empty( $args['customizer_repeater_link2_control'] ) ) {
            $this->customizer_repeater_link2_control = $args['customizer_repeater_link2_control'];
        }
		if ( ! empty( $args['customizer_repeater_link3_control'] ) ) {
            $this->customizer_repeater_link3_control = $args['customizer_repeater_link3_control'];
        }
		if ( ! empty( $args['customizer_repeater_link4_control'] ) ) {
            $this->customizer_repeater_link4_control = $args['customizer_repeater_link4_control'];
        }
		if ( ! empty( $args['customizer_repeater_link5_control'] ) ) {
            $this->customizer_repeater_link5_control = $args['customizer_repeater_link5_control'];
        }
		if ( ! empty( $args['customizer_repeater_link6_control'] ) ) {
            $this->customizer_repeater_link6_control = $args['customizer_repeater_link6_control'];
        }
		if ( ! empty( $args['customizer_repeater_link7_control'] ) ) {
            $this->customizer_repeater_link7_control = $args['customizer_repeater_link7_control'];
        }		
		if ( ! empty( $args['customizer_repeater_button_text_control'] ) ) {
			$this->customizer_repeater_button_text_control = $args['customizer_repeater_button_text_control'];
		}
		if ( ! empty( $args['customizer_repeater_button_link_control'] ) ) {
			$this->customizer_repeater_button_link_control = $args['customizer_repeater_button_link_control'];
		}
		if ( ! empty( $args['customizer_repeater_button2_text_control'] ) ) {
            $this->customizer_repeater_button2_text_control = $args['customizer_repeater_button2_text_control'];
        }
		if ( ! empty( $args['customizer_repeater_button2_link_control'] ) ) {
            $this->customizer_repeater_button2_link_control = $args['customizer_repeater_button2_link_control'];
        }
		
		if ( ! empty( $args['customizer_repeater_designation_control'] ) ) {
			$this->customizer_repeater_designation_control = $args['customizer_repeater_designation_control'];
		}		
		
		if ( ! empty( $args['customizer_repeater_checkbox_control'] ) ) {
			$this->customizer_repeater_checkbox_control = $args['customizer_repeater_checkbox_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_checkbox2_control'] ) ) {
			$this->customizer_repeater_checkbox2_control = $args['customizer_repeater_checkbox2_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_newtab_control'] ) ) {
			$this->customizer_repeater_newtab_control = $args['customizer_repeater_newtab_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_nofollow_control'] ) ) {
			$this->customizer_repeater_nofollow_control = $args['customizer_repeater_nofollow_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_video_url_control'] ) ) {
			$this->customizer_repeater_video_url_control = $args['customizer_repeater_video_url_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_slide_align'] ) ) {
			$this->customizer_repeater_slide_align = $args['customizer_repeater_slide_align'];
		}

		if ( ! empty( $args['customizer_repeater_shortcode_control'] ) ) {
			$this->customizer_repeater_shortcode_control = $args['customizer_repeater_shortcode_control'];
		}

		if ( ! empty( $args['customizer_repeater_repeater_control'] ) ) {
			$this->customizer_repeater_repeater_control = $args['customizer_repeater_repeater_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_repeater_img_control'] ) ) {
			$this->customizer_repeater_repeater_img_control = $args['customizer_repeater_repeater_img_control'];
		}
		

		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		if ( file_exists( get_template_directory() . '/inc/customizer-repeater/inc/icons.php' ) ) {
			$this->customizer_icon_container =  'inc/customizer-repeater/inc/icons';
		}

		$allowed_array1 = wp_kses_allowed_html( 'post' );
		$allowed_array2 = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'placeholder' => array()
			)
		);

		$this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
	}

	/*Enqueue resources for the control*/
	public function enqueue() {
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), 999 );

		wp_enqueue_style( 'webique_customizer-repeater-admin-stylesheet', get_template_directory_uri() . '/inc/customizer-repeater/css/admin-style.css', array(), 999 );

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'webique_customizer-repeater-script', get_template_directory_uri() . '/inc/customizer-repeater/js/customizer_repeater.js', array('jquery', 'jquery-ui-draggable', 'wp-color-picker' ), 999, true  );

		wp_enqueue_script( 'webique_customizer-repeater-fontawesome-iconpicker', get_template_directory_uri() . '/inc/customizer-repeater/js/fontawesome-iconpicker.js', array( 'jquery' ), 999, true );

		wp_enqueue_style( 'webique_customizer-repeater-fontawesome-iconpicker-script', get_template_directory_uri() . '/inc/customizer-repeater/css/fontawesome-iconpicker.min.css', array(), 999 );
	}

	public function render_content() {

		/*Get default options*/
		$this_default = json_decode( $this->setting->default );

		/*Get values (json format)*/
		$values = $this->value();

		/*Decode values*/
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		} ?>

		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( ( count( $json ) == 1 && '' === $json[0] ) || empty( $json ) ) {
				if ( ! empty( $this_default ) ) {
					$this->iterate_array( $this_default ); ?>
					<input type="hidden"
					       id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					       class="customizer-repeater-colector"
					       value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>"/>
					<?php
				} else {
					$this->iterate_array(); ?>
					<input type="hidden"
					       id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					       class="customizer-repeater-colector"/>
					<?php
				}
			} else {
				$this->iterate_array( $json ); ?>
				<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
				       class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
				<?php
			} ?>
			</div>
		<button type="button" class="button add_field customizer-repeater-new-field">
			<?php echo esc_html( $this->add_field_label ); ?>
		</button>
		<?php
	}

	private function iterate_array($array = array()){
		/*Counter that helps checking if the box is first and should have the delete button disabled*/
		$it = 0;
		if(!empty($array)){
			$exist_service=count($array);
			
				$webique_del_btn_id=$this->boxtitle;
			
			global $webique_limit;
			global $webique_type_with_id;
			echo sprintf("<input type='hidden' value='$exist_service' id='exist_webique_$webique_del_btn_id'/>");
			foreach($array as $icon){ 
			if($it<4)
			{
			$webique_limit="webique_limit";
			$webique_type_with_id='';
			}
			else
			{
			$webique_limit="webique_overlimit";	
			$webique_type_with_id=$webique_del_btn_id."_".$it;
			}

		
		?>
				<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
					<div class="customizer-repeater-customize-control-title">
						<?php echo esc_html( $this->boxtitle ) ?>
					</div>
					<div class="customizer-repeater-box-content-hidden">
						<?php
						$choice = $image_url = $image_url2 = $image_url3 = $icon_value = $icon2_value = $title = $subtitle = $subtitle2 = $description = $description2  = $text = $text2 = $text3 = $text4 = $text5 = $text6 = $text7 = $link = $link2 = $link3 = $link4 = $link5 = $link6 = $link7 = $button = $button2  = $button_link = $button2_link  = $designation = $slide_align = $newtab = $enable = $enable2 = $nofollow = $shortcode = $repeater = $repeater_img = $color = $color2 = $video_url = '';
						if(!empty($icon->id)){
							$id = $icon->id;
						}
						if(!empty($icon->choice)){
							$choice = $icon->choice;
						}
						if(!empty($icon->image_url)){
							$image_url = $icon->image_url;
						}
						if(!empty($icon->image_url2)){
							$image_url2 = $icon->image_url2;
						}
						if(!empty($icon->image_url3)){
							$image_url3 = $icon->image_url3;
						}
						if(!empty($icon->icon_value)){
							$icon_value = $icon->icon_value;
						}
						if(!empty($icon->icon2_value)){
							$icon2_value = $icon->icon2_value;
						}
						if(!empty($icon->color)){
							$color = $icon->color;
						}
						if(!empty($icon->color2)){
							$color2 = $icon->color2;
						}
						if(!empty($icon->title)){
							$title = $icon->title;
						}
						
						if(!empty($icon->slide_align)){
							$slide_align = $icon->slide_align;
						}
						
						if(!empty($icon->designation)){
							$designation = $icon->designation;
						}
						
						if(!empty($icon->subtitle)){
							$subtitle =  $icon->subtitle;
						}
						
						if(!empty($icon->subtitle2)){
							$subtitle2 =  $icon->subtitle2;
						}
						
						if(!empty($icon->description)){
							$description =  $icon->description;
						}
						
						if(!empty($icon->description2)){
							$description2 =  $icon->description2;
						}
						
						if(!empty($icon->text)){
							$text = $icon->text;
						}
						if(!empty($icon->text2)){
                            $text2 = $icon->text2;
                        }
						if(!empty($icon->text3)){
                            $text3 = $icon->text3;
                        }
						if(!empty($icon->text4)){
                            $text4 = $icon->text4;
                        }
						if(!empty($icon->text5)){
                            $text5 = $icon->text5;
                        }
						if(!empty($icon->text6)){
                            $text6 = $icon->text6;
                        }
						if(!empty($icon->text7)){
                            $text7 = $icon->text7;
                        }
						
						if(!empty($icon->button)){
                            $button = $icon->button;
                        }
						if(!empty($icon->button_link)){
							$button_link = $icon->button_link;
						}
						
						if(!empty($icon->button2)){
                            $button2 = $icon->button2;
                        }
						if(!empty($icon->button2_link)){
							$button2_link = $icon->button2_link;
						}
						
						 if(!empty($icon->link)){
                            $link = $icon->link;
                        }
						 if(!empty($icon->link2)){
                            $link2 = $icon->link2;
                        }
						 if(!empty($icon->link3)){
                            $link3 = $icon->link3;
                        }
						 if(!empty($icon->link4)){
                            $link4 = $icon->link4;
                        }
						 if(!empty($icon->link4)){
                            $link4 = $icon->link4;
                        }
						 if(!empty($icon->link5)){
                            $link5 = $icon->link5;
                        }
						 if(!empty($icon->link6)){
                            $link6 = $icon->link6;
                        }
						 if(!empty($icon->link7)){
                            $link7 = $icon->link7;
                        }
						if(!empty($icon->video_url)){
							$video_url = $icon->video_url;
						}
						
						
						if(!empty($icon->shortcode)){
							$shortcode = $icon->shortcode;
						}

						if(!empty($icon->social_repeater)){
							$repeater = $icon->social_repeater;
						}
						if(!empty($icon->repeater_img)){
							$repeater_img = $icon->repeater_img;
						}
						
						if(!empty($icon->enable)){
							$enable = $icon->enable;
						}
						
						if(!empty($icon->enable2)){
							$enable2 = $icon->enable2;
						}
						
						if(!empty($icon->newtab)){
							$newtab = $icon->newtab;
						}
						
						if(!empty($icon->nofollow)){
							$nofollow = $icon->nofollow;
						}
						
						
						if($this->customizer_repeater_title_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Title','webique' ), $this->id, 'customizer_repeater_title_control' ),
								'class' => 'customizer-repeater-title-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
							), $title);
						}
						
						if($this->customizer_repeater_subtitle_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Subtitle','webique' ), $this->id, 'customizer_repeater_subtitle_control' ),
								'class' => 'customizer-repeater-subtitle-control '."$webique_limit".' '."$webique_type_with_id".'',
								'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
							), $subtitle);
						}
						
						if($this->customizer_repeater_subtitle2_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Subtitle 2','webique' ), $this->id, 'customizer_repeater_subtitle2_control' ),
								'class' => 'customizer-repeater-subtitle2-control '."$webique_limit".' '."$webique_type_with_id".'',
								'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle2_control' ),
							), $subtitle2);
						}
						
						if($this->customizer_repeater_description_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Description','webique' ), $this->id, 'customizer_repeater_description_control' ),
								'class' => 'customizer-repeater-description-control '."$webique_limit".' '."$webique_type_with_id".'',
								'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_description_control' ),
							), $description);
						}
						
						if($this->customizer_repeater_description2_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Description 2','webique' ), $this->id, 'customizer_repeater_description2_control' ),
								'class' => 'customizer-repeater-description2-control '."$webique_limit".' '."$webique_type_with_id".'',
								'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_description2_control' ),
							), $description2);
						}
						
						if($this->customizer_repeater_button_text_control){
							$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__('Button Text',
							'webique'), $this->id, 'customizer_repeater_button_text_control'),
							'class' => 'customizer-repeater-button-text-control '."$webique_limit".'',
							'type' => apply_filters('webique_repeater_input_types_filter', '' , $this->id,
							'customizer_repeater_button_text_control'),
							), $button);
						}						
						if($this->customizer_repeater_button_link_control){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Button Link','webique' ), $this->id, 'customizer_repeater_button_link_control' ),
								'class' => 'customizer-repeater-button-link-control '."$webique_limit".' '."$webique_type_with_id".'',
								'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_button_link_control' ),
							), $button_link);
						}
						
						if($this->customizer_repeater_button2_text_control){
							$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__('Button 2 Text',
							'webique'), $this->id, 'customizer_repeater_button2_text_control'),
							'class' => 'customizer-repeater-button2-text-control '."$webique_limit".' '."$webique_type_with_id".'',
							'type' => apply_filters('webique_repeater_input_types_filter', '' , $this->id,
							'customizer_repeater_button2_text_control'),
							), $button2);
						}						
						if($this->customizer_repeater_button2_link_control){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Button 2 Link','webique' ), $this->id, 'customizer_repeater_button2_link_control' ),
								'class' => 'customizer-repeater-button2-link-control '."$webique_limit".' '."$webique_type_with_id".'',
								'sanitize_callback' => 'esc_url_raw',
								'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_button2_link_control' ),
							), $button2_link);
						}						
						
						if($this->customizer_repeater_text_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text','webique' ), $this->id, 'customizer_repeater_text_control' ),
								'class' => 'customizer-repeater-text-control '."$webique_limit".' '."$webique_type_with_id".'',
								'type'  => apply_filters('webique_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
							), $text);
						}
						if($this->customizer_repeater_link_control){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link','webique' ), $this->id, 'customizer_repeater_link_control' ),
								'class' => 'customizer-repeater-link-control '."$webique_limit".' '."$webique_type_with_id".'',
								'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
							), $link);
						}
						
						if($this->customizer_repeater_text2_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 2','webique' ), $this->id, 'customizer_repeater_text2_control' ),
                                'class' => 'customizer-repeater-text2-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text2_control' ),
                            ), $text2);
                        }
						if($this->customizer_repeater_link2_control){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 2','webique' ), $this->id, 'customizer_repeater_link2_control' ),
                                'class' => 'customizer-repeater-link2-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
                            ), $link2);
                        }
						
						if($this->customizer_repeater_text3_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 3','webique' ), $this->id, 'customizer_repeater_text3_control' ),
                                'class' => 'customizer-repeater-text3-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text3_control' ),
                            ), $text3);
                        }
						if($this->customizer_repeater_link3_control){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 3','webique' ), $this->id, 'customizer_repeater_link3_control' ),
                                'class' => 'customizer-repeater-link3-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link3_control' ),
                            ), $link3);
                        }
						
						if($this->customizer_repeater_text4_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 4','webique' ), $this->id, 'customizer_repeater_text4_control' ),
                                'class' => 'customizer-repeater-text4-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text4_control' ),
                            ), $text4);
                        }
						if($this->customizer_repeater_link4_control){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 4','webique' ), $this->id, 'customizer_repeater_link4_control' ),
                                'class' => 'customizer-repeater-link4-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link4_control' ),
                            ), $link4);
                        }
						
						if($this->customizer_repeater_text5_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 5','webique' ), $this->id, 'customizer_repeater_text5_control' ),
                                'class' => 'customizer-repeater-text5-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text5_control' ),
                            ), $text5);
                        }						
						if($this->customizer_repeater_link5_control){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 5','webique' ), $this->id, 'customizer_repeater_link5_control' ),
                                'class' => 'customizer-repeater-link5-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link5_control' ),
                            ), $link5);
                        }
						
						if($this->customizer_repeater_text6_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 6','webique' ), $this->id, 'customizer_repeater_text6_control' ),
                                'class' => 'customizer-repeater-text6-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text6_control' ),
                            ), $text6);
                        }
						if($this->customizer_repeater_link6_control){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 6','webique' ), $this->id, 'customizer_repeater_link6_control' ),
                                'class' => 'customizer-repeater-link6-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link6_control' ),
                            ), $link6);
                        }
						
						if($this->customizer_repeater_text7_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 7','webique' ), $this->id, 'customizer_repeater_text7_control' ),
                                'class' => 'customizer-repeater-text7-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text7_control' ),
                            ), $text7);
                        }
						if($this->customizer_repeater_link7_control){
                            $this->input_control(array(
                                'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 7','webique' ), $this->id, 'customizer_repeater_link7_control' ),
                                'class' => 'customizer-repeater-link7-control '."$webique_limit".' '."$webique_type_with_id".'',
                                'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link7_control' ),
                            ), $link7);
                        }
						
						if($this->customizer_repeater_checkbox_control == true){
							$this->testimonila_check_enable('enable');							
						}
					
						if($this->customizer_repeater_checkbox2_control == true){
							$this->testimonila_check_enable('enable2');							
						}
					
						if($this->customizer_repeater_newtab_control == true){
							$this->testimonila_check_enable('newtab');							
						}
					
						if($this->customizer_repeater_nofollow_control == true){
							$this->testimonila_check_enable('nofollow');							
						}
					
						if($this->customizer_repeater_video_url_control){
							$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__('Video Url',
							'webique'), $this->id, 'customizer_repeater_video_url_control'),
							'class' => 'customizer-repeater-video-url-control',
							'type'  => apply_filters('webique_customizer_repeater_video_url_control', 'textarea', $this->id, 'customizer_repeater_video_url_control' ),
							), $video_url);
						}
						
						if($this->customizer_repeater_slide_align == true){
							$this->slide_align($slide_align);
							
						}						
						
						if($this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true) {
							$this->icon_type_choice( $choice,$webique_limit );
						}
						if($this->customizer_repeater_image_control == true){
							$this->image_control($image_url, $choice, $webique_limit, $it+1, $webique_del_btn_id);
						}
						if($this->customizer_repeater_image2_control == true){
							$this->image_control2($image_url2, $choice, $webique_limit, $it+1, $webique_del_btn_id);
						}
						if($this->customizer_repeater_image3_control == true){
							$this->image_control3($image_url3, $choice, $webique_limit, $it+1, $webique_del_btn_id);
						}
						if($this->customizer_repeater_image4_control == true){
							$this->image_control4($image_url4, $choice, $webique_limit, $it+1, $webique_del_btn_id);
						}
						if($this->customizer_repeater_icon_control == true){
							$this->icon_picker_control($icon_value, $choice);
						}
						if($this->customizer_repeater_icon2_control == true){
							$this->icon_picker_control2($icon2_value, $choice);
						}					
						
						
						if($this->customizer_repeater_color_control == true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Color','webique' ), $this->id, 'customizer_repeater_color_control' ),
								'class' => 'customizer-repeater-color-control',
								'type'  => apply_filters('webique_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
								'sanitize_callback' => 'sanitize_hex_color'
							), $color);
						}
						if($this->customizer_repeater_color2_control == true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Color 2','webique' ), $this->id, 'customizer_repeater_color2_control' ),
								'class' => 'customizer-repeater-color2-control',
								'type'  => apply_filters('webique_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color2_control' ),
								'sanitize_callback' => 'sanitize_hex_color'
							), $color2);
						}
						
						
						if($this->customizer_repeater_shortcode_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Shortcode','webique' ), $this->id, 'customizer_repeater_shortcode_control' ),
								'class' => 'customizer-repeater-shortcode-control',
                                'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
							), $shortcode);
						}
						
						if($this->customizer_repeater_designation_control==true){
							$this->input_control(array(
								'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Designation','webique' ), $this->id, 'customizer_repeater_designation_control' ),
								'class' => 'customizer-repeater-designation-control',
								'type'  => apply_filters('webique_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_designation_control' ),
							), $designation);
						}
						
						if($this->customizer_repeater_repeater_control==true){
							$this->repeater_control($repeater, $webique_limit, $webique_type_with_id);
						}
						if($this->customizer_repeater_repeater_img_control==true){
							// $this->repeater_control($repeater_img, $webique_limit, $webique_type_with_id);
						}
						 ?>

						<input type="hidden" class="social-repeater-box-id" value="<?php if ( ! empty( $id ) ) {
							echo esc_attr( $id );
						} ?>">
						<button type="button" class="social-repeater-general-control-remove-field" <?php if ( $it == 0 ) {
							echo esc_attr('style="display:none;"');
						} ?>>
							<?php printf( __( 'Delete %s', 'webique' ), $this->boxtitle );	?>
						</button>

					</div>
				</div>

				<?php
				$it++;
			}
		} else { ?>
			<div class="customizer-repeater-general-control-repeater-container">
				<div class="customizer-repeater-customize-control-title">
					<?php echo esc_html( $this->boxtitle ) ?>
				</div>
				<div class="customizer-repeater-box-content-hidden">
					<?php
					if ( $this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true ) {
						$this->icon_type_choice();
					}
					if ( $this->customizer_repeater_image_control == true ) {
						$this->image_control();
					}
					if ( $this->customizer_repeater_image2_control == true ) {
						$this->image_control2();
					}
					if ( $this->customizer_repeater_image3_control == true ) {
						$this->image_control3();
					}
					if ( $this->customizer_repeater_image4_control == true ) {
						$this->image_control4();
					}
					if ( $this->customizer_repeater_icon_control == true ) {
						$this->icon_picker_control();
					}
					if ( $this->customizer_repeater_icon2_control == true ) {
						$this->icon_picker_control2();
					}
					
					
						
					if($this->customizer_repeater_color_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Color','webique' ), $this->id, 'customizer_repeater_color_control' ),
							'class' => 'customizer-repeater-color-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
							'sanitize_callback' => 'sanitize_hex_color'
						) );
					}
					if($this->customizer_repeater_color2_control == true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Color 2','webique' ), $this->id, 'customizer_repeater_color2_control' ),
							'class' => 'customizer-repeater-color2-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color2_control' ),
							'sanitize_callback' => 'sanitize_hex_color'
						));
					}
					
					if ( $this->customizer_repeater_title_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Title','webique' ), $this->id, 'customizer_repeater_title_control' ),
							'class' => 'customizer-repeater-title-control',
                            'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
						) );
					}
					
					if ( $this->customizer_repeater_subtitle_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Subtitle','webique' ), $this->id, 'customizer_repeater_subtitle_control' ),
							'class' => 'customizer-repeater-subtitle-control',
                            'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
						) );
					}
					
					if ( $this->customizer_repeater_subtitle2_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Subtitle 2','webique' ), $this->id, 'customizer_repeater_subtitle2_control' ),
							'class' => 'customizer-repeater-subtitle2-control',
                            'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle2_control' ),
						) );
					}
					
					if($this->customizer_repeater_description_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Description','webique' ), $this->id, 'customizer_repeater_description_control' ),
							'class' => 'customizer-repeater-description-control '."$webique_limit".' '."$webique_type_with_id".'',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_description_control' ),
						));
					}
					
					if($this->customizer_repeater_description2_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Description 2','webique' ), $this->id, 'customizer_repeater_description2_control' ),
							'class' => 'customizer-repeater-description2-control '."$webique_limit".' '."$webique_type_with_id".'',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_description2_control' ),
						));
					}
					
					if($this->customizer_repeater_button_text_control){
						$this->input_control(array(
						'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__('Button Text',
						'webique'), $this->id, 'customizer_repeater_button_text_control'),
						'class' => 'customizer-repeater-button-text-control',
						'type' => apply_filters('webique_repeater_input_types_filter', '' , $this->id,
						'customizer_repeater_button_text_control'),
						));
					}						
					if($this->customizer_repeater_button_link_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Button Link','webique' ), $this->id, 'customizer_repeater_button_link_control' ),
							'class' => 'customizer-repeater-button-link-control '."$webique_limit".' '."$webique_type_with_id".'',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_button_link_control' ),
						));
					}
					
					if($this->customizer_repeater_button2_text_control){
						$this->input_control(array(
						'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__('Button 2 Text',
						'webique'), $this->id, 'customizer_repeater_button2_text_control'),
						'class' => 'customizer-repeater-button2-text-control',
						'type' => apply_filters('webique_repeater_input_types_filter', '' , $this->id,
						'customizer_repeater_button2_text_control'),
						));
					}						
					if($this->customizer_repeater_button2_link_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Button 2 Link','webique' ), $this->id, 'customizer_repeater_button2_link_control' ),
							'class' => 'customizer-repeater-button2-link-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_button2_link_control' ),
						));
					}
					
					if($this->customizer_repeater_text_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text','webique' ), $this->id, 'customizer_repeater_text_control' ),
							'class' => 'customizer-repeater-text-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
						));
					}
					if($this->customizer_repeater_link_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link','webique' ), $this->id, 'customizer_repeater_link_control' ),
							'class' => 'customizer-repeater-link-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
						));
					}
					
					if($this->customizer_repeater_text2_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 2','webique' ), $this->id, 'customizer_repeater_text2_control' ),
							'class' => 'customizer-repeater-text2-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text2_control' ),
						));
					}
					if($this->customizer_repeater_link2_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 2','webique' ), $this->id, 'customizer_repeater_link2_control' ),
							'class' => 'customizer-repeater-link2-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
						));
					}
					
					if($this->customizer_repeater_text3_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 3','webique' ), $this->id, 'customizer_repeater_text3_control' ),
							'class' => 'customizer-repeater-text3-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text3_control' ),
						));
					}
					if($this->customizer_repeater_link3_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 3','webique' ), $this->id, 'customizer_repeater_link3_control' ),
							'class' => 'customizer-repeater-link3-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link3_control' ),
						));
					}
					
					if($this->customizer_repeater_text4_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 4','webique' ), $this->id, 'customizer_repeater_text4_control' ),
							'class' => 'customizer-repeater-text4-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text4_control' ),
						));
					}
					if($this->customizer_repeater_link4_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 4','webique' ), $this->id, 'customizer_repeater_link4_control' ),
							'class' => 'customizer-repeater-link4-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link4_control' ),
						));
					}
					
					if($this->customizer_repeater_text5_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 5','webique' ), $this->id, 'customizer_repeater_text5_control' ),
							'class' => 'customizer-repeater-text5-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text5_control' ),
						));
					}						
					if($this->customizer_repeater_link5_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 5','webique' ), $this->id, 'customizer_repeater_link5_control' ),
							'class' => 'customizer-repeater-link5-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link5_control' ),
						));
					}
					
					if($this->customizer_repeater_text6_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 6','webique' ), $this->id, 'customizer_repeater_text6_control' ),
							'class' => 'customizer-repeater-text6-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text6_control' ),
						));
					}
					if($this->customizer_repeater_link6_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 6','webique' ), $this->id, 'customizer_repeater_link6_control' ),
							'class' => 'customizer-repeater-link6-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link6_control' ),
						));
					}
					
					if($this->customizer_repeater_text7_control==true){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Text 7','webique' ), $this->id, 'customizer_repeater_text7_control' ),
							'class' => 'customizer-repeater-text7-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text7_control' ),
						));
					}
					if($this->customizer_repeater_link7_control){
						$this->input_control(array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Link 7','webique' ), $this->id, 'customizer_repeater_link7_control' ),
							'class' => 'customizer-repeater-link7-control',
							'sanitize_callback' => 'esc_url_raw',
							'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link7_control' ),
						));
					}					
					
						if($this->customizer_repeater_checkbox_control == true){
							$this->testimonila_check_enable();							
						}
					
						if($this->customizer_repeater_checkbox2_control == true){
							$this->testimonila_check_enable();							
						}
					
						if($this->customizer_repeater_newtab_control == true){
							$this->testimonila_check_enable();							
						}
					
						if($this->customizer_repeater_nofollow_control == true){
							$this->testimonila_check_enable();							
						}
					
			
					if($this->customizer_repeater_video_url_control){
						$this->input_control(array(
						'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__('Video Url',
						'webique'), $this->id, 'customizer_repeater_video_url_control'),
						'class' => 'customizer-repeater-video-url-control',
						'type'  => apply_filters('webique_customizer_repeater_video_url_control', 'textarea', $this->id, 'customizer_repeater_video_url_control' ),
						));
					}
					
					if($this->customizer_repeater_slide_align == true){
							$this->slide_align($slide_align);							
					}					
					
					if ( $this->customizer_repeater_shortcode_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Shortcode','webique' ), $this->id, 'customizer_repeater_shortcode_control' ),
							'class' => 'customizer-repeater-shortcode-control',
                            'type'  => apply_filters('webique_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
						) );
					}
					
					
					if ( $this->customizer_repeater_designation_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('webique_repeater_input_labels_filter', esc_html__( 'Designation','webique' ), $this->id, 'customizer_repeater_designation_control' ),
							'class' => 'customizer-repeater-designation-control',
							'type'  => apply_filters('webique_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_designation_control' ),
						) );
					}
					
					if($this->customizer_repeater_repeater_control==true){
						$this->repeater_control();
					} ?>
					<input type="hidden" class="social-repeater-box-id">
					<button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
						<?php esc_html_e( 'Delete field', 'webique' ); ?>
					</button>
				</div>
			</div>
			<?php
		}
	}

	private function input_control( $options, $value='' ){
//print_r($options);
	?>
		<span class="customize-control-title <?php echo esc_html( $options['label'] ); ?>" 
		<?php if($options['class']== 'customizer-repeater-video-url-control') {echo esc_attr('style="display:none;"'); }?>
		
		><?php echo esc_html( $options['label'] ); ?></span>
		<?php
		if( !empty($options['type']) ){
			switch ($options['type']) {
				case 'textarea':?>
					<textarea class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"><?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?></textarea>
					<?php
                    break;
				case 'color': ?>
					<input type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" />
					<?php
					break;
			}
		} else { ?>
			<input type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
			<?php
		}
	}
	
	
	private function testimonila_check_enable($webique_type_with_id ){
		?>
	<div class="customize-control-title">
	<?php if($webique_type_with_id == 'enable' || $webique_type_with_id =='enable2'): ?>
	<?php esc_html_e('Enable Setting:','webique'); ?>
	<?php elseif($webique_type_with_id == 'newtab'): ?>
	<?php esc_html_e('Open In New Tab:','webique'); ?>
	<?php else: ?>
	<?php esc_html_e('Add "nofollow" To Link:','webique'); ?>
	<?php endif; ?>
	<span class="switch">
		<input type="checkbox" name="custom_checkbox-<?php echo $webique_type_with_id; ?>" class="customizer-repeater-checkbox-<?php echo $webique_type_with_id; ?>">
	</span>
	</div>
	<?php
	}

	private function icon_picker_control($value = '', $show = '', $class=''){ ?>
		<div class="social-repeater-general-control-icon" <?php if( $show === 'customizer_repeater_image' || $show === 'customizer_repeater_none' ) { echo esc_attr('style="display:none;"'); } ?>>
            <span class="customize-control-title">
                <?php esc_html_e('Icon','webique'); ?>
            </span>
			<span class="description customize-control-description">
                <?php
                echo sprintf(
	                esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %1$s.', 'webique' ),
	                sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>', esc_html__( 'http://fontawesome.io/icons/', 'webique' ) )
                ); ?>
            </span>
			<div class="input-group icp-container">
				<input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value );} ?>" type="text">
				<span class="input-group-addon">
                    <i class="fa <?php echo esc_attr($value); ?>"></i>
                </span>
			</div>
            <?php get_template_part( $this->customizer_icon_container ); ?>
		</div>
		<?php
	}

		private function image_control($value = '', $show = '', $class='', $auto='', $sections=''){ 
		if($auto==1)
		{
			$auto="one";
		}

		if($auto==2)
		{
			$auto="two";
		}
		if($auto==3)
		{
			$auto="three";
		}
		if($auto==4)
		{
			$auto="four";
		}
		?>
		<div class="customizer-repeater-image-control" <?php if( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' ) { echo esc_attr('style="display:none;"'); } ?>>
            <span class="customize-control-title">
                <?php esc_html_e('Image','webique')?>
            </span>
			<input type="text" class="widefat custom-media-url <?php if($class="webique_overlimit") { echo esc_attr('webique-uploading-img');}?> <?php echo esc_attr($auto);?>" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button <?php if($class="webique_overlimit") { echo esc_attr('webique-uploading-img-btn');}?> <?php echo esc_attr($auto);?>" value="<?php esc_attr_e( 'Upload Image','webique' ); ?>" />
		</div>
		<?php
	}

	private function image_control2($value = '', $show = '', $class='', $auto='', $sections=''){ 
		if($auto==1)
		{
			$auto="one";
		}

		if($auto==2)
		{
			$auto="two";
		}
		if($auto==3)
		{
			$auto="three";
		}
		if($auto==4)
		{
			$auto="four";
		}
		?>
		<div class="customizer-repeater-image2-control">
            <span class="customize-control-title">
                <?php esc_html_e('Image 2','webique')?>
            </span>
			<input type="text" class="widefat custom-media-url2 <?php if($class="webique_overlimit") { echo esc_attr('webique-uploading-img');}?> <?php echo esc_attr($auto);?>" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button <?php if($class="webique_overlimit") { echo esc_attr('webique-uploading-img-btn');}?> <?php echo esc_attr($auto);?>" value="<?php esc_attr_e( 'Upload Image','webique' ); ?>" />
		</div>
		<?php
	}

	private function image_control3($value = '', $show = '', $class='', $auto='', $sections=''){ 
		if($auto==1)
		{
			$auto="one";
		}

		if($auto==2)
		{
			$auto="two";
		}
		if($auto==3)
		{
			$auto="three";
		}
		if($auto==4)
		{
			$auto="four";
		}
		?>
		<div class="customizer-repeater-image3-control">
            <span class="customize-control-title">
                <?php esc_html_e('Image 3','webique')?>
            </span>
			<input type="text" class="widefat custom-media-url3 <?php if($class="webique_overlimit") { echo esc_attr('webique-uploading-img');}?> <?php echo esc_attr($auto);?>" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button <?php if($class="webique_overlimit") { echo esc_attr('webique-uploading-img-btn');}?> <?php echo esc_attr($auto);?>" value="<?php esc_attr_e( 'Upload Image','webique' ); ?>" />
		</div>
		<?php
	}

	private function slide_align($value='left'){?>
	
	<span class="customize-control-title">
	<?php esc_html_e('Slide Align','webique'); ?>
	</span>
	<select class="customizer-repeater-slide-align">
		<option value="left" <?php selected($value,'left');?>>
		<?php esc_html_e('Left','webique') ?>
		</option>
		
		<option value="right" <?php selected($value,'right');?>>
		<?php esc_html_e('Right','webique') ?>
		</option>
		
		<option value="center" <?php selected($value,'center');?>>
		<?php esc_html_e('Center','webique') ?>
		</option>
		
		
	</select>
		
	<?php
	}
	
	private function icon_type_choice($value='customizer_repeater_icon', $webique_limit=''){ ?>
		<span class="customize-control-title">
            <?php esc_html_e('Image type','webique');?>
        </span>
		<select class="customizer-repeater-image-choice  <?php echo esc_attr($webique_limit);?>">
			<option value="customizer_repeater_icon" <?php selected($value,'customizer_repeater_icon');?>><?php esc_html_e('Icon','webique'); ?></option>
			<option value="customizer_repeater_image" <?php selected($value,'customizer_repeater_image');?>><?php esc_html_e('Image','webique'); ?></option>
			<option value="customizer_repeater_none" <?php selected($value,'customizer_repeater_none');?>><?php esc_html_e('None','webique'); ?></option>
		</select>
		<?php
	}

	private function repeater_control($value = '', $webique_limit='', $webique_type_with_id=''){
		$social_repeater = array();
		$show_del        = 0; ?>
		<span class="customize-control-title"><?php esc_html_e( 'Social icons', 'webique' ); ?></span>
		<?php
		if(!empty($value)) {
			$social_repeater = json_decode( html_entity_decode( $value ), true );
		}
		if ( ( count( $social_repeater ) == 1 && '' === $social_repeater[0] ) || empty( $social_repeater ) ) { ?>
			<div class="customizer-repeater-social-repeater">
				<div class="customizer-repeater-social-repeater-container">
					<div class="customizer-repeater-rc input-group icp-container">
						<input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value ); } ?>" type="text">
						<span class="input-group-addon"></span>
					</div>
					<?php get_template_part( $this->customizer_icon_container ); ?>
					<input type="text" class="customizer-repeater-social-repeater-link team_linkdata_<?php echo $webique_limit;?> <?php echo esc_attr($webique_type_with_id);?>"
					       placeholder="<?php esc_attr_e( 'Link', 'webique' ); ?>">
					<input type="hidden" class="customizer-repeater-social-repeater-id" value="">
					<button class="social-repeater-remove-social-item" style="display:none">
						<?php esc_html_e( 'Remove Icon', 'webique' ); ?>
					</button>
				</div>
				<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value=""/>
			</div>
			<button class="social-repeater-add-social-item button-secondary "><?php esc_html_e( 'Add Icon', 'webique' ); ?></button>
			<?php
		} else { ?>
			<div class="customizer-repeater-social-repeater">
				<?php
				foreach ( $social_repeater as $social_icon ) {
					$show_del ++; ?>
					<div class="customizer-repeater-social-repeater-container">
						<div class="customizer-repeater-rc input-group icp-container">
							<input data-placement="bottomRight" class="icp icp-auto team_data_<?php echo esc_attr($webique_limit);?> <?php echo esc_attr($webique_type_with_id);?>" value="<?php if( !empty($social_icon['icon']) ) { echo esc_attr( $social_icon['icon'] ); } ?>" type="text">
							<span class="input-group-addon"><i class="fa <?php echo esc_attr( $social_icon['icon'] ); ?>"></i></span>
						</div>
						<?php get_template_part( $this->customizer_icon_container ); ?>
						<input type="text" class="customizer-repeater-social-repeater-link"
						       placeholder="<?php esc_attr_e( 'Link', 'webique' ); ?>"
						       value="<?php if ( ! empty( $social_icon['link'] ) ) {
							       echo esc_url( $social_icon['link'] );
						       } ?>">
						<input type="hidden" class="customizer-repeater-social-repeater-id"
						       value="<?php if ( ! empty( $social_icon['id'] ) ) {
							       echo esc_attr( $social_icon['id'] );
						       } ?>">
						<button class="social-repeater-remove-social-item"
						        style="<?php if ( $show_del == 1 ) {
							        echo esc_attr("display:none");
						        } ?>"><?php esc_html_e( 'Remove Icon', 'webique' ); ?></button>
					</div>
					<?php
				} ?>
				<input type="hidden" id="social-repeater-socials-repeater-colector"
				       class="social-repeater-socials-repeater-colector"
				       value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
			</div>
			<button class="social-repeater-add-social-item button-secondary <?php echo esc_attr($webique_limit);?> <?php echo esc_attr($webique_type_with_id);?>"><?php esc_html_e( 'Add Icon', 'webique' ); ?></button>
			<?php
		}
	}
	
	private function repeater_control2() {
			$images = json_decode( $this->value(), true );
			if ( empty( $images ) ) {
				$images = array();
			}
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			</label>
			<div class="image-repeater-wrapper">
				<?php foreach ( $images as $image_url ) : ?>
					<div class="image-repeater-item">
						<img src="<?php echo esc_url( $image_url ); ?>" style="max-width:100px;">
						<input type="hidden" class="image-repeater-url" value="<?php echo esc_url( $image_url ); ?>">
						<button class="button remove-image"><?php _e( 'Remove', 'webique' ); ?></button>
					</div>
				<?php endforeach; ?>
			</div>
			<button class="button add-image"><?php _e( 'Add Image', 'webique' ); ?></button>
			<input type="hidden" class="image-repeater-collector" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>">
			<?php
		}
}


/**
  * Filter to modify input label in repeater control
  * You can filter by control id and input name.
  *
  * @param string $string Input label.
  * @param string $id Input id.
  * @param string $control Control name.
  * @modified 1.1.41
  *
  * @return string
  */
 function webique_repeater_labels( $string, $id, $control ) {
	if ( $id === 'testimonial_contents' ) {
		if ( $control === 'customizer_repeater_title_control' ) {
			return esc_html__( 'Review Title','webique' );
		}
		
		if ( $control === 'customizer_repeater_subtitle_control' ) {
			return esc_html__( 'Review Text','webique' );
		}
		
		if ( $control === 'customizer_repeater_subtitle2_control' ) {
			return esc_html__( 'Name','webique' );
		}
		
		if ( $control === 'customizer_repeater_text2_control' ) {
			return esc_html__( 'No. of Star','webique' );
		}
		if ( $control === 'customizer_repeater_text3_control' ) {
			return esc_html__( 'Designation','webique' );
		}
		if ( $control === 'customizer_repeater_image_control' ) {
			return esc_html__( 'Customer Image','webique' );
		}
	}
	
	if ( $id === 'service_contents'  || $id === 'pg_service_contents') {
		if ( $control === 'customizer_repeater_subtitle_control' ) {
			return esc_html__( 'Service 1','webique' );
		}
		
		if ( $control === 'customizer_repeater_subtitle2_control' ) {
			return esc_html__( 'Service 2','webique' );
		}
		
		if ( $control === 'customizer_repeater_subtitle3_control' ) {
			return esc_html__( 'Service 3','webique' );
		}
		
		if ( $control === 'customizer_repeater_subtitle4_control' ) {
			return esc_html__( 'Service 4','webique' );
		}
		
		if ( $control === 'customizer_repeater_subtitle5_control' ) {
			return esc_html__( 'Service 5','webique' );
		}
		
	}
	
	
	return $string;
 }
 add_filter( 'webique_repeater_input_labels_filter','webique_repeater_labels', 10 , 3 );