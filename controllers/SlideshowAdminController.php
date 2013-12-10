<?php
/**
@Prefix('admin/slideshow')
*/
class SlideshowAdminController extends \Coxis\Admin\Libs\Controller\AdminParentController {
	#todo deleteSingleFile

	public function formConfigure() {
		$controller = $this;
		$form = new \Coxis\Admin\Libs\Form\AdminSimpleForm($this, 'slideshow');
		$form->images = new \Coxis\Form\DynamicGroup(function($data) use($controller) {
			if($data !== null)
				if($data === '' || (is_array($data) && !array_filter(Tools::flateArray($data))))
					return;
			return new \Coxis\Admin\Libs\Form\AdminModelForm(new \Coxis\Slideshow\Models\Slide, $controller);
		});
		foreach(\Coxis\Slideshow\Models\Slide::orderBy('id ASC')->get() as $k=>$a){
			$form->images[$k] = new \Coxis\Admin\Libs\Form\AdminModelForm($a, $this);
		}
		$form->hasfile = true;

		$controller = $this;
		$form->images->setDefaultRender(function($field) use($form, $controller) {
			return 	'<div class="slide">'.$form->h4('Image'.($field->getModel()->isOld() ? ' <a href="'.$controller->url_for('delete', array('id'=>$field->getModel()->id)).'" style="font-size:10px">'.__('Supprimer').'</a>':'')).
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
			\Flash::addSuccess(__('Slide deleted with success.'));
		return \Response::back();
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
				if(\POST::get('send') !== null)
					return \Response::redirect(\URL::url_for(array('Coxis\Admin\Controllers\DefaultAdmin', 'index')));
			} catch(\Coxis\Form\FormException $e) {
				\Flash::addError($this->form->getGeneralErrors());
				\Response::setCode(400);
			}
		}
		elseif(!$this->form->uploadSuccess()) {
			\Flash::addError(__('Data exceeds upload size limit. Maybe your file is too heavy.'));
			\Response::setCode(400);
		}
		$this->setRelativeView('form.php');
	}
}