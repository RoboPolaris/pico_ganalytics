<?php

/**
 * PicoGAnalytics -  Google Analytics for PicoCMS
 *
 * This plugin will automatically add the Google Analytics tracking script
 * to the <head> section of each page generated by Pico. To change the
 * default configuration, copy and paste the array below in your config.php
 *
 * //PicoGAnalytics Configuration
 * $config['PicoGAnalytics'] = array(
 *     'enabled' => true,              //Enable PicoGAnalytics
 *     'trackingID' => 'UA-XXXXXX-X',  //Your Property ID *required*
 *     'displayFeatures' => false,     //Option: Display Features
 *     'linkAtt' => false,             //Option: Link Attribution
 *     'ecommerce' => false            //Option: Ecommerce
 * );
 *
 * @author  Brice Boucard
 * @link    https://github.com/bricebou/pico_ganalytics
 * @license http://opensource.org/licenses/MIT
 *
 * @author  Tyler Heshka <tyler@heshka.com>
 * @version 1.1
 */

class PicoGAnalytics extends AbstractPicoPlugin
{
    /**
     * This plugin is enabled by default
     *
     * @see AbstractPicoPlugin::$enabled
     */
    protected $enabled = true;

    /**
     * This plugin depends on no other plugins.
     *
     * @see AbstractPicoPlugin::$dependsOn
     */
    protected $dependsOn = null;

    /**
     * Google Tracking ID
     * @link{https://support.google.com/analytics/answer/1032385}
     */
    private $googleTrackingId;

    /**
     * Site Tite
     * Loaded from the config.php in Pico::$configDir
     */
    private $siteTitle;

    /**
     * Option: Display Features (Default: off)
     * @link{https://developers.google.com/analytics/devguides/collection/analyticsjs/display-features}
     */
    private $displayFeatures = false;

    /**
     * Option: Enhanced Link Attribution (Default: off)
     * @link{https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-link-attribution}
     */
    private $linkAtt = false;
    /**
     * Option: Ecommerce (Default: off)
     * @link{https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce}
     */
    private $ecommerce = false;

    /**
     * Triggered after Pico readed its configuration
     *
     * @see    Pico::getConfig()
     * @param  array &$config array of config variables
     * @return void
     */
    public function onConfigLoaded(&$config)
    {
        //Check configuration for enabled
		if (isset($config['PicoGAnalytics']['enabled']) &&
        $config['PicoGAnalytics']['enabled'] === false) {
            //Set enabled state
            $this->enabled = false;
		}
        //Check configuration for ID
		if (isset($config['PicoGAnalytics']['trackingID'])) {
            //Set ID
            $this->googleTrackingId = $config['PicoGAnalytics']['trackingID'];
		}
        //Check configuration for Site Title
		if (isset($config['site_title'])) {
            //Set title
            $this->siteTitle = $config['site_title'];
		}
        //Check configuration for Display Features option
		if (isset($config['PicoGAnalytics']['displayFeatures']) &&
        $config['PicoGAnalytics']['displayFeatures'] === true ) {
            //Set option
            $this->displayFeatures = true;
		}
        //Check configuration for Link Attribution option
		if (isset($config['PicoGAnalytics']['linkAtt']) &&
        $config['PicoGAnalytics']['linkAtt'] === true) {
            //Set option
            $this->linkAtt = true;
		}
        //Check configuration for Ecommerce option
		if (isset($config['PicoGAnalytics']['ecommerce']) &&
        $config['PicoGAnalytics']['ecommerce'] === true) {
            //Set option
            $this->ecommerce = true;
		}
    }

    /**
     * Triggered after Pico rendered the page
     *
     * @param  string &$output contents which will be sent to the user
     * @return void
     */
    public function onPageRendered(&$output)
    {
        // Inject the JavaScript in the page head.
        $output = str_replace('</head>', (PHP_EOL . $this->build() . '</head>'), $output);
    }

    /**
     * Builds the JavaScript Tracking Code
     *
     * @param  void
     * @return string JavaScript Google tracking code
     */
    private function build()
    {
        //Check that Tracking ID is not empty!
        if (!empty($this->googleTrackingId)){
            //BEGIN SCRIPT
            $script = "    <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', '" . $this->googleTrackingId . "', '" . $this->siteTitle . "');";
            // OPTION: DISPLAY FEATURES
            if ($this->displayFeatures === true) {
            $script .= "
            ga('require', 'displayfeatures');";
            }
            // OPTION: LINK ATTRIBUTION
            if ($this->linkAtt === true) {
            $script .= "
            ga('require', 'linkid', 'linkid.js');";
            }
            // OPTION: ECOMMERCE
            if ($this->ecommerce === true) {
            $script .= "
            ga('require', 'ecommerce');";
            }
            //SEND, AND CLOSE THE SCRIPT
            $script .= "
            ga('send', 'pageview');
    </script>
";
            // END SCRIPT
            return $script;
        }
    }
}
