<?php
/**
 * MailPoet Piwik Add-on Tracker
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet Piwik Add-on/Classes
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_Piwik_Addon_Tracker' ) ) {

	/**
	 * MailPoet_Piwik_Addon_Tracker Class
	 */
	class MailPoet_Piwik_Addon_Tracker {

		/**
		 * mailpoet_piwik_tracking function.
		 * 
		 * @access public
		 * @return void
		 */
		function mailpoet_piwik_tracking( ) {
			$site_id = get_option('piwik_siteid');
			$domain = get_option('piwik_domain');
			$setDomains = get_option('piwik_setdomains');
			if($setDomains){
				$setDomains = explode(',', $setDomains);
			}

			if(!$site_id || !$domain) return;
		?>
		<script type="text/javascript">
			var _paq = _paq || [];
			<?php if($setDomains) echo "_paq.push(['setDomains', ['".implode("','", $setDomains)."']);"; ?>

			_paq.push(["trackPageView"]);
			_paq.push(["enableLinkTracking"]);

			(function() {
				var u=(("https:" == document.location.protocol) ? "https" : "http") + "://<?php echo $domain ?>/";
				_paq.push(["setTrackerUrl", u+"piwik.php"]);
				_paq.push(["setSiteId", "<?php echo $site_id ?>"]);
				var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
				g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
	})();
		</script>
		<?php
		}

	} // end if class.

} // end if class exists.

return new MailPoet_Piwik_Addon_Tracker();

?>