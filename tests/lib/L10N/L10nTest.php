<?php
/**
 * Copyright (c) 2016 Joas Schilling <nickvergessen@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace Test\L10N;


use DateTime;
use OC\L10N\Factory;
use OC\L10N\L10N;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IUserSession;
use Test\TestCase;

/**
 * Class L10nTest
 *
 * @package Test\L10N
 */
class L10nTest extends TestCase {
	/**
	 * @return Factory
	 */
	protected function getFactory() {
		/** @var \OCP\IConfig $config */
		$config = $this->createMock(IConfig::class);
		/** @var \OCP\IRequest $request */
		$request = $this->createMock(IRequest::class);
		/** @var IUserSession $userSession */
		$userSession = $this->createMock(IUserSession::class);
		return new Factory($config, $request, $userSession, \OC::$SERVERROOT);
	}

	public function testGermanPluralTranslations() {
		$transFile = \OC::$SERVERROOT.'/tests/data/l10n/de.json';
		$l = new L10N($this->getFactory(), 'test', 'de', 'de_AT', [$transFile]);

		$this->assertEquals('1 Datei', (string) $l->n('%n file', '%n files', 1));
		$this->assertEquals('2 Dateien', (string) $l->n('%n file', '%n files', 2));
	}

	public function testRussianPluralTranslations() {
		$transFile = \OC::$SERVERROOT.'/tests/data/l10n/ru.json';
		$l = new L10N($this->getFactory(), 'test', 'ru', 'ru_UA',[$transFile]);

		$this->assertEquals('1 файл', (string)$l->n('%n file', '%n files', 1));
		$this->assertEquals('2 файла', (string)$l->n('%n file', '%n files', 2));
		$this->assertEquals('6 файлов', (string)$l->n('%n file', '%n files', 6));
		$this->assertEquals('21 файл', (string)$l->n('%n file', '%n files', 21));
		$this->assertEquals('22 файла', (string)$l->n('%n file', '%n files', 22));
		$this->assertEquals('26 файлов', (string)$l->n('%n file', '%n files', 26));

		/*
		  1 file	1 файл	1 папка
		2-4 files	2-4 файла	2-4 папки
		5-20 files	5-20 файлов	5-20 папок
		21 files	21 файл	21 папка
		22-24 files	22-24 файла	22-24 папки
		25-30 files	25-30 файлов	25-30 папок
		etc
		100 files	100 файлов,	100 папок
		1000 files	1000 файлов	1000 папок
		*/
	}

	public function testCzechPluralTranslations() {
		$transFile = \OC::$SERVERROOT.'/tests/data/l10n/cs.json';
		$l = new L10N($this->getFactory(), 'test', 'cs', 'cs_CZ', [$transFile]);

		$this->assertEquals('1 okno', (string)$l->n('%n window', '%n windows', 1));
		$this->assertEquals('2 okna', (string)$l->n('%n window', '%n windows', 2));
		$this->assertEquals('5 oken', (string)$l->n('%n window', '%n windows', 5));
	}

	public function localizationData() {
		return array(
			// timestamp as string
			array('February 13, 2009 at 11:31:30 PM GMT+0', 'en', 'en_US', 'datetime', '1234567890'),
			array('13. Februar 2009 um 23:31:30 GMT+0', 'de', 'de_DE', 'datetime', '1234567890'),
			array('February 13, 2009', 'en', 'en_US', 'date', '1234567890'),
			array('13. Februar 2009', 'de', 'de_DE', 'date', '1234567890'),
			array('11:31:30 PM GMT+0', 'en', 'en_US', 'time', '1234567890'),
			array('23:31:30 GMT+0', 'de', 'de_DE', 'time', '1234567890'),

			// timestamp as int
			array('February 13, 2009 at 11:31:30 PM GMT+0', 'en', 'en_US', 'datetime', 1234567890),
			array('13. Februar 2009 um 23:31:30 GMT+0', 'de', 'de_DE', 'datetime', 1234567890),
			array('February 13, 2009', 'en', 'en_US', 'date', 1234567890),
			array('13. Februar 2009', 'de', 'de_DE', 'date', 1234567890),
			array('11:31:30 PM GMT+0', 'en', 'en_US', 'time', 1234567890),
			array('23:31:30 GMT+0', 'de', 'de_DE', 'time', 1234567890),

			// DateTime object
			array('February 13, 2009 at 11:31:30 PM GMT+0', 'en', 'en_US', 'datetime', new DateTime('@1234567890')),
			array('13. Februar 2009 um 23:31:30 GMT+0', 'de', 'de_DE', 'datetime', new DateTime('@1234567890')),
			array('February 13, 2009', 'en', 'en_US', 'date', new DateTime('@1234567890')),
			array('13. Februar 2009', 'de', 'de_DE', 'date', new DateTime('@1234567890')),
			array('11:31:30 PM GMT+0', 'en', 'en_US', 'time', new DateTime('@1234567890')),
			array('23:31:30 GMT+0', 'de', 'de_DE', 'time', new DateTime('@1234567890')),

			// en_GB
			array('13 February 2009 at 23:31:30 GMT+0', 'en_GB', 'en_GB', 'datetime', new DateTime('@1234567890')),
			array('13 February 2009', 'en_GB', 'en_GB', 'date', new DateTime('@1234567890')),
			array('23:31:30 GMT+0', 'en_GB', 'en_GB', 'time', new DateTime('@1234567890')),
			array('13 February 2009 at 23:31:30 GMT+0', 'en-GB', 'en_GB', 'datetime', new DateTime('@1234567890')),
			array('13 February 2009', 'en-GB', 'en_GB', 'date', new DateTime('@1234567890')),
			array('23:31:30 GMT+0', 'en-GB', 'en_GB', 'time', new DateTime('@1234567890')),
		);
	}

	/**
	 * @dataProvider localizationData
	 */
	public function testNumericStringLocalization($expectedDate, $lang, $locale, $type, $value) {
		$l = new L10N($this->getFactory(), 'test', $lang, $locale, []);
		$this->assertSame($expectedDate, $l->l($type, $value));
	}

	public function firstDayData() {
		return array(
			array(1, 'de', 'de_DE'),
			array(0, 'en', 'en_US'),
		);
	}

	/**
	 * @dataProvider firstDayData
	 * @param $expected
	 * @param $lang
	 * @param $locale
	 */
	public function testFirstWeekDay($expected, $lang, $locale) {
		$l = new L10N($this->getFactory(), 'test', $lang, $locale, []);
		$this->assertSame($expected, $l->l('firstday', 'firstday'));
	}

	public function jsDateData() {
		return array(
			array('dd.MM.yy', 'de', 'de_DE'),
			array('M/d/yy', 'en', 'en_US'),
		);
	}

	/**
	 * @dataProvider jsDateData
	 * @param $expected
	 * @param $lang
	 * @param $locale
	 */
	public function testJSDate($expected, $lang, $locale) {
		$l = new L10N($this->getFactory(), 'test', $lang, $locale, []);
		$this->assertSame($expected, $l->l('jsdate', 'jsdate'));
	}

	public function testFactoryGetLanguageCode() {
		$l = $this->getFactory()->get('lib', 'de');
		$this->assertEquals('de', $l->getLanguageCode());
	}

	public function testServiceGetLanguageCode() {
		$l = \OC::$server->getL10N('lib', 'de');
		$this->assertEquals('de', $l->getLanguageCode());
	}

	public function testWeekdayName() {
		$l = \OC::$server->getL10N('lib', 'de');
		$this->assertEquals('Mo.', $l->l('weekdayName', new \DateTime('2017-11-6'), ['width' => 'abbreviated']));
	}
}
