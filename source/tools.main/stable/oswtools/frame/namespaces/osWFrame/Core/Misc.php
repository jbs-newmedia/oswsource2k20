<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace osWFrame\Core;

class Misc {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * Misc constructor.
	 */
	private function __construct() {

	}

	/**
	 * Gibt den Useragent zurück.
	 *
	 * @return string
	 */
	public static function getUserAgent():string {
		return isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
	}

	/**
	 * Prüft ob es sich um einen Bot/Crawler handelt.
	 * Stand: 31.10.2019
	 *
	 * @link https://github.com/fabiomb/is_bot
	 * @param string $useragent
	 * @return bool
	 */
	public static function checkCrawler(string $useragent=''):bool {
		if ($useragent=='') {
			$useragent=self::getUserAgent();
		}
		$bots=['Googlebot', 'Baiduspider', 'ia_archiver', 'R6_FeedFetcher', 'NetcraftSurveyAgent', 'Sogou web spider', 'bingbot', 'Yahoo! Slurp', 'facebookexternalhit', 'PrintfulBot', 'msnbot', 'Twitterbot', 'UnwindFetchor', 'urlresolver', 'Butterfly', 'TweetmemeBot', 'PaperLiBot', 'MJ12bot', 'AhrefsBot', 'Exabot', 'Ezooms', 'YandexBot', 'SearchmetricsBot', 'picsearch', 'TweetedTimes Bot', 'QuerySeekerSpider', 'ShowyouBot', 'woriobot', 'merlinkbot', 'BazQuxBot', 'Kraken', 'SISTRIX Crawler', 'R6_CommentReader', 'magpie-crawler', 'GrapeshotCrawler', 'PercolateCrawler', 'MaxPointCrawler', 'R6_FeedFetcher', 'NetSeer crawler', 'grokkit-crawler', 'SMXCrawler', 'PulseCrawler', 'Y!J-BRW', '80legs.com/webcrawler', 'Mediapartners-Google', 'Spinn3r', 'InAGist', 'Python-urllib', 'NING', 'TencentTraveler', 'Feedfetcher-Google', 'mon.itor.us', 'spbot', 'Feedly', 'bitlybot', 'ADmantX Platform', 'Niki-Bot', 'Pinterest', 'python-requests', 'DotBot', 'HTTP_Request2', 'linkdexbot', 'A6-Indexer', 'Baiduspider', 'TwitterFeed', 'Microsoft Office', 'Pingdom', 'BTWebClient', 'KatBot', 'SiteCheck', 'proximic', 'Sleuth', 'Abonti', '(BOT for JCE)', 'Baidu', 'Tiny Tiny RSS', 'newsblur', 'updown_tester', 'linkdex', 'baidu', 'searchmetrics', 'genieo', 'majestic12', 'spinn3r', 'profound', 'domainappender', 'VegeBot', 'terrykyleseoagency.com', 'CommonCrawler Node', 'AdlesseBot', 'metauri.com', 'libwww-perl', 'rogerbot-crawler', 'MegaIndex.ru', 'ltx71', 'Qwantify', 'Traackr.com', 'Re-Animator Bot', 'Pcore-HTTP', 'BoardReader', 'omgili', 'okhttp', 'CCBot', 'Java/1.8', 'semrush.com', 'feedbot', 'CommonCrawler', 'AdlesseBot', 'MetaURI', 'ibwww-perl', 'rogerbot', 'MegaIndex', 'BLEXBot', 'FlipboardProxy', 'techinfo@ubermetrics-technologies.com', 'trendictionbot', 'Mediatoolkitbot', 'trendiction', 'ubermetrics', 'ScooperBot', 'TrendsmapResolver', 'Nuzzel', 'Go-http-client', 'Applebot', 'LivelapBot', 'GroupHigh', 'SemrushBot', 'ltx71', 'commoncrawl', 'istellabot', 'DomainCrawler', 'cs.daum.net', 'StormCrawler', 'GarlikCrawler', 'The Knowledge AI', 'getstream.io/winds', 'YisouSpider', 'archive.org_bot', 'semantic-visions.com', 'FemtosearchBot', '360Spider', 'linkfluence.com', 'glutenfreepleasure.com', 'Gluten Free Crawler', 'YaK/1.0', 'Cliqzbot', 'app.hypefactors.com', 'axios', 'semantic-visions.com', 'webdatastats.com', 'schmorp.de', 'SEOkicks', 'DuckDuckBot', 'Barkrowler', 'ZoominfoBot', 'Linguee Bot', 'Mail.RU_Bot', 'OnalyticaBot', 'Linguee Bot', 'admantx-adform', 'Buck/2.2', 'Barkrowler', 'Zombiebot', 'Nutch', 'SemanticScholarBot', 'Jetslide', 'scalaj-http', 'XoviBot', 'sysomos.com', 'PocketParser', 'newspaper', 'serpstatbot', 'MetaJobBot', 'SeznamBot/3.2', 'VelenPublicWebCrawler/1.0', 'WordPress.com mShots', 'adscanner', 'BacklinkCrawler', 'netEstate NE Crawler', 'Astute SRM', 'GigablastOpenSource/1.0', 'DomainStatsBot', 'Winds: Open Source RSS & Podcast', 'dlvr.it', 'BehloolBot', '7Siters', 'AwarioSmartBot', 'Apache-HttpClient/5'];
		foreach ($bots as $b) {
			if (stripos($useragent, $b)!==false)
				return true;
		}

		return false;
	}

}

?>