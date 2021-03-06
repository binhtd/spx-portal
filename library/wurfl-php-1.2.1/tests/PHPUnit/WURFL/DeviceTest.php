<?php

require_once 'PHPUnit/Framework.php';

class WURFL_DeviceTest extends PHPUnit_Framework_TestCase {
	
	protected $wurflManager;
	protected $testData;
	
	const TEST_DATA_FILE = "../../resources/device-capability.yml";
	
	protected function setUp() {
		$this->wurflManager = $this->sharedFixture;
	}
	
	/**
	 * @dataProvider deviceIdCapabilityNameCapabilityValueProvider
	 */
	public function testGetCapability($deviceId, $capabilityName, $capabilityValue) {
		$device = $this->wurflManager->getDevice ( $deviceId );
		$capabilityFound = $device->getCapability ( $capabilityName );
		
		$this->assertEquals ( $capabilityValue, $capabilityFound );
	}
	
	public function deviceIdCapabilityNameCapabilityValueProvider() {
		return array (
            array ("ericsson_t20_ver1", "resolution_width", "101" ),
            array ("ericsson_t20_ver1", "resolution_width", "101" ),
            array ("ericsson_t20_ver1", "resolution_height", "33" ),
            array ("ericsson_t20_ver1", "brand_name", "Ericsson" ),
            array ("ericsson_t20_ver1", "icons_on_menu_items_support", "false" ),
		    array ("verizon_lge_vx8100_ver1", "ringtone_midi_monophonic", "false" ),
            array ("verizon_lge_vx8100_ver1", "gif_animated", "true" ),
            array ("verizon_lge_vx8100_ver1", "xhtml_format_as_css_property", "true" ),
            array ("verizon_lge_vx8100_ver1", "oma_v_1_0_forwardlock", "true" ),
		    array ("kwc_kx9_ver1", "brand_name", "Kyocera" ),
            array ("kwc_kx9_ver1", "xhtml_marquee_as_css_property", "true" ),
            array ("kwc_kx9_ver1", "oma_v_1_0_forwardlock", "true" ),
            array ("kwc_kx9_ver1", "html_wi_imode_html_1", "true" ),
            array ("kwc_kx9_ver1", "menu_with_list_of_links_recommended", "false" ),
            array ("kwc_kx9_ver1", "insert_br_element_after_widget_recommended", "false" ),
		    array ("blackberry7780_ver1", "model_name", "BlackBerry 7780" ),
            array ("blackberry7780_ver1", "midi_monophonic", "true" ),
            array ("blackberry7780_ver1", "html_wi_w3_xhtmlbasic", "true" ),
            array ("nokia_6610_ver1", "mp3", "false" ),
            array ("nokia_6610_ver1", "max_deck_size", "65535" ),
            array ("nokia_6300_ver1_sub0470", "mp3", "false" ),
            array ("firefox_3", "brand_name", "firefox" ),
            array ("firefox_3", "model_name", "3" ),
            array ("firefox_2", "is_wireless_device", "false" ),
            array ("firefox_2", "resolution_width", "800" ),
            array ("firefox_2", "resolution_height", "600" )
        );
	
	}

}

