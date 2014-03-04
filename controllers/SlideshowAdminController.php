<?php
/**
@Prefix('admin/slideshow')
*/
class SlideshowAdminController extends \Coxis\Admin\Libs\Controller\AdminParentController {
	public function formConfigure() {
		$controller = $this;
		$form = new \Coxis\Admin\Libs\Form\AdminSimpleForm($this, 'slideshow');
		$form->images = new \Coxis\Form\DynamicGroup(function($data) use($controller) {
			if($data !== null)
				if($data === '' || (is_array($data) && !array_filter(Tools::flateArray($data))))
					return;
			return new \Coxis\Admin\Libs\Form\AdminEntityForm(new \Coxis\Slideshow\Entities\Slide, $controller);
		});
		foreach(\Coxis\Slideshow\Entities\Slide::orderBy('id ASC')->get() as $k=>$a){
			$form->images[$k] = new \Coxis\Admin\Libs\Form\AdminEntityForm($a, $this);
		}
		$form->hasfile = true;

		$controller = $this;
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
			\Coxis\Core\App::get('flash')->addSuccess(__('Slide deleted with success.'));
		return \Coxis\Core\App::get('response')->back();
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
				if(\Coxis\Core\App::get('post')->get('send') !== null)
					return \Coxis\Core\App::get('response')->redirect(\Coxis\Core\App::get('url')->url_for(array('Coxis\Admin\Controllers\DefaultAdmin', 'index')));
			} catch(\Coxis\Form\FormException $e) {
				\Coxis\Core\App::get('flash')->addError($this->form->getGeneralErrors());
				\Coxis\Core\App::get('response')->setCode(400);
			}
		}
		elseif(!$this->form->uploadSuccess()) {
			\Coxis\Core\App::get('flash')->addError(__('Data exceeds upload size limit. Maybe your file is too heavy.'));
			\Coxis\Core\App::get('response')->setCode(400);
		}
		$this->setRelativeView('form.php');
	}
}