/**
 * MailPoet Piwik Add-on Admin JS
 *
 * @author: Sebs Studio
 * @version: 1.0.0
 */
jQuery(function(){

	jQuery('input#piwikenabled').change(function() {
		if (jQuery(this).is(':checked')) {
			jQuery('#piwikcampaignname').closest('tr').show();
			jQuery('#piwikcampaignkeyword').closest('tr').show();
		}
		else {
			jQuery('#piwikcampaignname').closest('tr').hide();
			jQuery('#piwikcampaignkeyword').closest('tr').hide();
		}
	}).change();

});