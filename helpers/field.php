<?php 

defined('C5_EXECUTE') or die('Access Denied.');

/* 
	This helper will take a field and output the proper field input HTML
*/

class FieldHelper {

	public function output($key, $field, $data = array()) {	
		if ($field['multi'] && !empty($data[$key])) {
			$html = '';
			foreach ($data[$key] as $value) {
				$html .= '<div class="multi-clone">';
				$html .= $this->generateHtml($key.'[]', $field, $value);
				$html .= '</div>';
			}
			return $html;
		}
		else {
			$value = $data[$key];
			$key = ($field['multi']) ? $key.'[]' : $key;
			return $this->generateHtml($key, $field, $value, $data);
		}
	}
	
	private function generateHtml($key, $field, $value = null, $data = array()) {
		$form = Loader::helper('form');
		$dtt = Loader::helper('form/date_time');
		$al = Loader::helper('concrete/asset_library');
		$ps = Loader::helper('form/page_selector');
		
		switch ($field['type']) {
			case 'text':
				$html = $form->text($key, $value);
				break;
			case 'textarea':
				$html = $form->textarea($key, $value);
				break;
			case 'datetime':
				if ($field['required']) {
					$html = $dtt->datetime($key, $value);
				}
				else {
					$checked = (isset($value)) ? ' checked' : '';
					$html = '<input type="checkbox" class="date-toggle" value="yes" name="'.$key.'-toggle"'.$checked.'>'.$dtt->datetime($key, $value);
				}
				break;
			case 'date':
				$html = $dtt->date($key, $value);
				break;
			case 'boolean':
				$html = $form->select($key, array(
					'0' => 'No',
					'1' => 'Yes'
				), $value);
				break;
			case 'select':
				$html = $form->select($key, $field['options'], $value);
				break;
			case 'combo':
				$html = $form->select($key, $field['options'], $value, array('class' => 'combobox'));
				$html .= '
					<script type="text/javascript" charset="utf-8">
						$(".combobox").combobox();
					</script>
				';
				break;
			case 'page':
				$html .= $ps->selectPage($key, $data[$key]);
				break;
			case 'wysiwyg':
				Loader::element('editor_controls', array('mode'=>'full'));
				$html = $form->textarea($key, $value, array('style' => 'width:100%;', 'class' => 'ccm-advanced-editor'));
				break;
			case 'checkbox_list':
				$html = $this->buildCheckboxList($field['items'], 'tags', $data);
				break;
			case 'image':
				if (isset($value)) {
					$file = File::getByID($value);
				}
				$html = $al->image($key, $key, 'Choose image...', $file);
				break;
			case 'file':
				if (isset($value)) {
					$file = File::getByID($value);
				}
				$html = $al->file($key, $key, 'Choose file...', $file);
				break;
		}
		return $html;
	}
	
	private function buildCheckboxList($checkboxes, $groupName = 'options', $data = array()) {
		$html = '';
		$form = Loader::helper('form');
		
		foreach ($checkboxes as $handle => $name) {
			$checked = (isset($data[$groupName][$handle])) ? true : false;
			$html .= '<label class="checkbox-item">'.$form->checkbox($groupName.'[]', $handle, $checked).$name.'</label>';
		}
		return $html;
	}
	
}