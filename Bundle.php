<?php
namespace Coxis\Slideshow;

class Bundle extends \Coxis\Core\BundleLoader {
	public function run() {
		Config::set('slideshow', 'width', 1000);
		Config::set('slideshow', 'height', 768);

		\Coxis\Imagecache\Libs\ImageCache::addPreset('imagecache', array(
			'resize'	=>	array(
				'width'	 =>	Config::get('slideshow', 'width'),
			),
			'crop'	=>	array(
				'height' =>	Config::get('slideshow', 'height'),
			)
		));

		\Coxis\Admin\Libs\AdminMenu::instance()->menu[0]['childs'][] = array('label' => 'Slideshow', 'link' => 'slideshow');

		\Coxis\Admin\Libs\AdminMenu::instance()->home[] = array('img'=>\Coxis\Core\Facades\URL::to('slideshow/icon.svg'), 'link'=>'slideshow', 'title' => __('Slideshow'), 'description' => __('Slideshow images'));
		parent::run();
	}
}