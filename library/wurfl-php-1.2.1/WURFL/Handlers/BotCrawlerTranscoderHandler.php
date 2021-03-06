<?php
/**
 * WURFL API
 *
 * LICENSE
 *
 * This file is released under the GNU General Public License. Refer to the
 * COPYING file distributed with this package.
 *
 * Copyright (c) 2008-2009, WURFL-Pro S.r.l., Rome, Italy
 * 
 * 
 *
 * @category   WURFL
 * @package    WURFL_Handlers
 * @copyright  WURFL-PRO SRL, Rome, Italy
 * @license
 * @version    $id$
 */

/**
 * BotCrawlerTranscoderUserAgentHanlder
 * 
 *
 * @category   WURFL
 * @package    WURFL_Handlers
 * @copyright  WURFL-PRO SRL, Rome, Italy
 * @license
 * @version    $id$
 */
class WURFL_Handlers_BotCrawlerTranscoderHandler extends WURFL_Handlers_Handler {
	
	protected $prefix = "BOT_CRAWLER_TRANSCODER";
	
	function __construct($wurflContext, $userAgentNormalizer = null) {
		parent::__construct ( $wurflContext, $userAgentNormalizer );
	}
	
	/** 
	 *
	 * @param string $userAgent
	 * @return boolean 
	 */
	public function canHandle($userAgent) {
		foreach ( $this->botCrawlerTranscoder as $key ) {
			if (WURFL_Handlers_Utils::checkIfContainsCaseInsensitive ( $userAgent, $key )) {
				return true;
			}
		}
		return false;
	
	}


    private $botCrawlerTranscoder = array(
        "bot",
        "crawler",
        "spider",
        "novarra",
        "transcoder",
        "yahoo! searchmonkey",
        "yahoo! slurp",
        "feedfetcher-google",
        "toolbar",
        "mowser",
        "mediapartners-google",
        "azureus",
        "inquisitor",
        "baiduspider",
        "baidumobaider",
        "holmes/",
        "libwww-perl",
        "netSprint",
        "yandex",
        "cfnetwork",
        "ineturl",
        "jakarta",
        "lorkyll",
        "microsoft url control",
        "indy library",
        "slurp",
        "crawl",
        "wget",
        "ucweblient",
        "rma",
        "snoopy",
        "untrursted",
        "mozfdsilla",
        "ask jeeves",
        "jeeves/teoma",
        "mechanize",
        "http client",
        "servicemonitor",
        "httpunit",
        "hatena",
        "ichiro"
    );
		

}
