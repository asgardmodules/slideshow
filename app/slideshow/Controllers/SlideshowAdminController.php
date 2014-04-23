<?php
namespace Asgard\Slideshow\Controllers;

/**
@Prefix('admin/slideshow')
*/
class SlideshowAdminController extends \App\Admin\Libs\Controller\AdminParentController {
	public function formConfigure() {
		$controller = $this;

		$form = new \App\Admin\Libs\Form\AdminSimpleForm($this, 'slideshow');
		$form->images = new \Asgard\Form\DynamicGroup(function($data) use($controller) {
			if($data === '' || (is_array($data) && !array_filter(\Asgard\Utils\Tools::flateArray($data))))
				return;
			return new \App\Admin\Libs\Form\AdminEntityForm(new \Asgard\Slideshow\Entities\Slide, $controller);
		});
		foreach(\Asgard\Slideshow\Entities\Slide::orderBy('id ASC')->get() as $a)
			$form->images[] = new \App\Admin\Libs\Form\AdminEntityForm($a, $this);
		$form->hasfile = true;

		$form->images->setDefaultRender(function($field) use($form, $controller) {
			return 	'<div class="slide">'.$form->h4('Image'.($field->getEntity()->isOld() ? ' <a href="'.$controller->url_for('delete', array('id'=>$field->getEntity()->id)).'" style="font-size:10px">'.__('Supprimer').'</a>':'')).
				$field->image->def().
				$field->description->textarea().
				'</div>';
		});

		return $form;
	}

	/**
	@Route(':id/delete')
	*/
	public function deleteAction($request) {
		if(Slide::destroyOne($request['id']))
			\Asgard\Core\App::get('flash')->addSuccess(__('Slide deleted with success.'));
		return \Asgard\Core\App::get('response')->back();
	}

	/**
	@Route('')
	*/
	public function indexAction($request) {
		$this->form = $this->formConfigure();

		if($this->form->isSent()) {
			try {
				$this->form->save();
				Flash::addSuccess(__('The slideshow was saved with success.'));
				if(\Asgard\Core\App::get('post')->get('send') !== null)
					return \Asgard\Core\App::get('response')->redirect(\Asgard\Core\App::get('url')->url_for(array('App\Admin\Controllers\DefaultAdmin', 'index')));
			} catch(\Asgard\Form\FormException $e) {
				\Asgard\Core\App::get('flash')->addError($this->form->getGeneralErrors());
				\Asgard\Core\App::get('response')->setCode(400);
			}
		}
		elseif(!$this->form->uploadSuccess()) {
			\Asgard\Core\App::get('flash')->addError(__('Data exceeds upload size limit. Maybe your file is too heavy.'));
			\Asgard\Core\App::get('response')->setCode(400);
		}
		$this->setRelativeView('form.php');
	}
}