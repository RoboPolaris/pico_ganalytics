<?php 
/**
 * Pico GAnalytics
 *
 * Adds Google Analytics to your pages
 *
 * @author Brice Boucard
 * @link https://github.com/bricebou/pico_ganalytics/
 * @license http://bricebou.mit-license.org/
 */

class Pico_GAnalytics {

	public function config_loaded(&$settings)
	{	
		if (isset($settings['GAnalytics']['trackingID']))
        {
            $this->GA_ID = $settings['GAnalytics']['trackingID'];
        }
        if (isset($settings['GAnalytics']['demoint'])) {
        	$this->GA_demoint = $settings['GAnalytics']['demoint'];
        }
        if (isset($settings['GAnalytics']['linkatt'])) {
        	$this->GA_linkatt = $settings['GAnalytics']['linkatt'];
        }
	}

	public function build_ga()
	{
		if (isset($this->GA_ID) && $this->GA_ID != '') {
			$gascript = '
				<script>
					(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
			  		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				  	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				  	})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');
					
					ga(\'create\', \''.$this->GA_ID.'\', \'auto\');
			';

			if (isset($this->GA_demoint) && $this->GA_demoint === true) {
				$gascript .= '		ga(\'require\', \'displayfeatures\');
				';
			}
			if (isset($this->GA_linkatt) && $this->GA_linkatt === true) {
				$gascript .= '	ga(\'require\', \'linkid\', \'linkid.js\');
				';
			}
			$gascript .= '	ga(\'send\', \'pageview\');
				</script>
			';

			//$this->gascript = $gascript;
			return $gascript;
		}
	}

	public function after_render(&$output)
	{
		/*if(isset($this->GA_ID))
		{*/	
			$output = str_replace('</head>', PHP_EOL.$this->build_ga().'</head>', $output);
		//}
	}
}
?>