<?php
require_once('Controller.php');
require_once('Models/NewsModel.php');
require_once('Models/ScreenshotsModel.php');

class NewsPage extends Controller {
	private $_template;

	/* Constructor. */
	public function __construct() {
		parent::__construct();
		$this->_template = 'news.tpl';
	}

	/* Display the index page. */
	public function index() {
		$date = $_GET['d'];

		if ($date != null) {
			if (strtolower($date) == 'archive' || $date == '') {
				$date = null;
			}
			return $this->getNews($date);
		}
		return $this->getNewsIntro();
	}

	/* Display a specific news item, or all news items. */
	public function getNews($date = null) {
		if ($date == null) {
			$news_items = NewsModel::getAllNews();
		} else {
			$news_items = array(NewsModel::getByDate($date));
		}
		
		$this->addCSSFiles('news.css');
		
		return $this->renderPage(
			array(
				'title' => 'Home',
				'content_title' => 'Latest Developments',
				'show_intro' => false,
				'news_items' => $news_items,
				'news_archive_link' => false,
			),
			$this->_template
		);
	}

	/* Display the main page with limited news items and intro text. */
	public function getNewsIntro() {
		$news_items = NewsModel::getLatestNews(NEWS_ITEMS);
		$random_shot = ScreenshotsModel::getRandomScreenshot();

		$this->addCSSFiles(array(
			'intro.css',
			'news.css',
			'../javascripts/slimbox/css/slimbox2.css',
		));
		$this->addJSFiles(array(
			'jquery-1.3.2.min.js',
			'slimbox/js/slimbox2.js',
			'introduction.js',
		));


		return $this->renderPage(
			array(
				'title' => 'Home',
				'content_title' => 'Latest Developments',
				'show_intro' => true,
				'news_items' => $news_items,
				'news_archive_link' => true,
				'random_shot' => $random_shot,
			),
			$this->_template
		);
	}
}
?>