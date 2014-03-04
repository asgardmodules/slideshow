<?php
namespace Coxis\Slideshow;

class Bundle extends \Coxis\Core\BundleLoader {
	public function run() {
		\Coxis\Core\App::get('config')->set('slideshow', 'width', 1000);
		\Coxis\Core\App::get('config')->set('slideshow', 'height', 768);

		\Coxis\Imagecache\Libs\ImageCache::addPreset('imagecache', array(
			'resize'	=>	array(
				'width'	 =>	\Coxis\Core\App::get('config')->get('slideshow', 'width'),
			),
			'crop'	=>	array(
				'height' =>	\Coxis\Core\App::get('config')->get('slideshow', 'height'),
			)
		));

		\Coxis\Admin\Libs\AdminMenu::instance()->menu[0]['childs'][] = array('label' => 'Slideshow', 'link' => 'slideshow');

		\Coxis\Admin\Libs\AdminMenu::instance()->home[] = array('img'=>\Coxis\Core\Facades\Coxis\Core\App::get('url')->to('slideshow/icon.svg'), 'link'=>'slideshow', 'title' => __('Slideshow'), 'description' => __('Slideshow images'));
		parent::run();
	}
}