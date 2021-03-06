<?php
require_once dirname ( __FILE__ ) . '/../classautoloader.php';

/**
 * test case.
 */
class WURFL_Cache_APCCacheProviderTest extends PHPUnit_Framework_TestCase {
	
		
	public function testNeverToExpireItems() {
		$params = array(WURFL_Configuration_Config::EXPIRATION => WURFL_Cache_CacheProvider::NEVER);
				
		$cache = new WURFL_Cache_APCCacheProvider($params);
		
		$cache->put("foo", "foo");
		sleep(2);
		$this->assertEquals("foo", $cache->get("foo"));
				
	}
	
	/*
	 *  Need to make two request to test this.
	 *  http://pecl.php.net/bugs/bug.php?id=13331
	public function testShouldRemoveTheExpiredItem() {
		
		$params = array(WURFL_Configuration_Config::EXPIRATION => 1);				
		$cache = new WURFL_Cache_APCCacheProvider($params);
		$cache->put("key", "value");		
		sleep(2);
		$this->assertEquals(NULL, $cache->get("key"));
	}
	*/
	
	public function testShouldClearAllItems() {
		$cache = new WURFL_Cache_APCCacheProvider(array());
		$cache->put("key1", "item1");		
		$cache->put("key2", "item2");
		$cache->clear();
		
		$this->assertThanNoElementsAreInCache(array("key1", "key2"), $cache);
		
	}

	private function assertThanNoElementsAreInCache($keys = array(), $cache) {
		foreach ($keys as $key) {
			$this->assertNull($cache->get($key));
		}
	}
	
}
