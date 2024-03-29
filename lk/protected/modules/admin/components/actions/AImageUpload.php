<?php

/**
 * Redactor widget image upload action.
 *
 * @param string $attr Model attribute
 * @throws CHttpException
 */

class AImageUpload extends CAction
{
	public $uploadPath;
	public $uploadUrl;
	public $uploadCreate=false;
	public $permissions=0777;

	public function run($imagePath = '')
	{
		$name=strtolower($this->getController()->getId());
		$attribute=strtolower((string)$imagePath);

		if ($this->uploadPath===null) {
			$path=Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'uploads';
			$this->uploadPath=realpath($path);
			if ($this->uploadPath===false && $this->uploadCreate===true) {
				if (!mkdir($path,$this->permissions,true)) {
					throw new CHttpException(500,CJSON::encode(
						array('error'=>'Could not create upload folder "'.$path.'".')
					));
				}
			}
		}
		if ($this->uploadUrl===null) {
			$this->uploadUrl=Yii::app()->request->baseUrl .'/uploads';
		}

		// Make Yii think this is a AJAX request.
		$_SERVER['HTTP_X_REQUESTED_WITH']='XMLHttpRequest';

		$file=CUploadedFile::getInstanceByName('file');
		if ($file instanceof CUploadedFile) {
			$attributePath=$this->uploadPath.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.$attribute;
			if ($attribute)
				$attributePath .= DIRECTORY_SEPARATOR.$attribute;
			if (!in_array(strtolower($file->getExtensionName()),array('gif','png','jpg','jpeg'))) {
				throw new CHttpException(500,CJSON::encode(
					array('error'=>'Invalid file extension '. $file->getExtensionName().'.')
				));
			}
			$fileName=trim(md5($attribute.time().uniqid(rand(),true))).'.'.$file->getExtensionName();
			if (!is_dir($attributePath)) {
				if (!mkdir($attributePath,$this->permissions,true)) {
					throw new CHttpException(500,CJSON::encode(
						array('error'=>'Could not create folder "'.$attributePath.'". Make sure "uploads" folder is writable.')
					));
				}
			}
			$path=$attributePath.DIRECTORY_SEPARATOR.$fileName;
			if (file_exists($path) || !$file->saveAs($path)) {
				throw new CHttpException(500,CJSON::encode(
					array('error'=>'Could not save file or file exists: "'.$path.'".')
				));
			}
			$attributeUrl=$this->uploadUrl.'/'.$name;
			if ($attribute)
				$attributeUrl .= '/'.$attribute;
			$attributeUrl .= '/'.$fileName;
			$data = array(
				'filelink'=>$attributeUrl,
			);
			echo CJSON::encode($data);
			exit;
		} else {
			throw new CHttpException(500,CJSON::encode(
				array('error'=>'Could not upload file.')
			));
		}
	}
}