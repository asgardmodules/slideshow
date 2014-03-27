<?php
namespace Asgard\Slideshow;

class Bundle extends \Asgard\Core\BundleLoader {
	public function run() {
		\Asgard\Core\App::get('config')->set('slideshow', 'width', 1000);
		\Asgard\Core\App::get('config')->set('slideshow', 'height', 768);

		\Asgard\Imagecache\Libs\ImageCache::addPreset('imagecache', array(
			'resize'	=>	array(
				'width'	 =>	\Asgard\Core\App::get('config')->get('slideshow', 'width'),
			),
			'crop'	=>	array(
				'height' =>	\Asgard\Core\App::get('config')->get('slideshow', 'height'),
			)
		));

		\Asgard\Admin\Libs\AdminMenu::instance()->menu[0]['childs'][] = array('label' => 'Slideshow', 'link' => 'slideshow');

		\Asgard\Admin\Libs\AdminMenu::instance()->home[] = array('img'=>\Asgard\Core\App::get('url')->to('slideshow/icon.svg'), 'link'=>'slideshow', 'title' => __('Slideshow'), 'description' => __('Slideshow images'));
		parent::run();
	}
}