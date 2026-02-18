( function( api ) {

	// Extends our custom "example-1" section.
	api.sectionConstructor['plugin-section'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );


function webiquefrontpagesectionsscroll( section_id ){
    var scroll_section_id = "slider-section";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {
        case 'accordion-section-features_setting':
        scroll_section_id = "features-section";
        break;

        case 'accordion-section-service_setting':
        scroll_section_id = "service-section";
        break;
		
        case 'accordion-section-portfolio_setting':
        scroll_section_id = "portfolio-section";
        break;
		
        case 'accordion-section-animation_bars_setting':
        scroll_section_id = "marquee-section-left";
        break;
		
        case 'accordion-section-pricing_setting':
        scroll_section_id = "pricing-section";
        break;
		
        case 'accordion-section-testimonial_setting':
        scroll_section_id = "testimonial-section";
        break;
		
        case 'accordion-section-team_setting':
        scroll_section_id = "team-section";
        break;
		
		case 'accordion-section-cta_setting':
        scroll_section_id = "cta-section";
        break;
		
		case 'accordion-section-business_setting':
        scroll_section_id = "business-section";
        break;
		
		case 'accordion-section-client_setting':
        scroll_section_id = "client-section";
        break;
		
		case 'accordion-section-blog_setting':
        scroll_section_id = "post-section";
    }

    if( $contents.find('#'+scroll_section_id).length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + scroll_section_id ).offset().top
        }, 1000);
    }
}

 jQuery('body').on('click', '#sub-accordion-panel-webique_frontpage_sections .control-subsection .accordion-section-title', function(event) {
        var section_id = jQuery(this).parent('.control-subsection').attr('id');
        webiquefrontpagesectionsscroll( section_id );
});

(function($) {
	$(document).ready(function($) {
		if($("select").hasClass("contact-icon-picker")){
			$('.contact-icon-picker.iconPicker').fontIconPicker();
		}
	});
	
})(jQuery);

jQuery(document).ready(function($) {
	 jQuery("#sub-accordion-section-client_setting select").prop('disabled', true);
	 jQuery("#sub-accordion-section-features_setting input[type='radio'], #sub-accordion-section-features_setting select ").prop('disabled', true);
	 jQuery("#sub-accordion-section-service_setting input[type='radio'], #sub-accordion-section-service_setting select ").prop('disabled', true);
	 jQuery("#sub-accordion-section-top_scroller .icons-selector").css({ 'pointer-events':'none', 'opacity':'0.7'});
	 jQuery("#sub-accordion-section-slider_setting select, #sub-accordion-section-slider_setting button.wp-color-result").prop('disabled', true);
	 jQuery("#customize-control-site_ttl_size input, #customize-control-site_desc_size input").prop('disabled', true).css('background','#dddddd50');
	 jQuery("#customize-control-cta_bg_position input, #customize-control-cta_bg_overlay_clr button.wp-color-result, #customize-control-cta_bg_opacity input, #customize-control-cta_effect_enable input").prop('disabled', true).css('background','#dddddd50');
});