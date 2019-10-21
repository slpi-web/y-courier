<?php

/**
 * Redactor widget image list action.
 *
 * @param string $attr Model attribute
 */

class AImageList extends CAction
{
	public $uploadPath;
	public $uploadUrl;
    public $useFolders = true;

	public function run($imagePath = '')
	{
        $name=strtolower($this->getController()->getId());
        $attribute=strtolower((string)$imagePath);

        if ($this->uploadPath===null) {
            $path=Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'uploads';
            $this->uploadPath=realpath($path);
            if ($this->uploadPath===false) {
                exit;
            }
        }
        if ($this->uploadUrl===null) {
            $this->uploadUrl=Yii::app()->request->baseUrl .'/uploads';
        }

        $attributePath=$this->uploadPath.DIRECTORY_SEPARATOR.$name;
        $attributeUrl=str_replace('//', '/', ($this->uploadUrl.'/'.$name.'/'));
        if ($attribute) {
            $attributePath .= DIRECTORY_SEPARATOR . $attribute;
            $attributeUrl .= $attribute.'/';
        }

        $files=CFileHelper::findFiles($attributePath, array('fileTypes'=>array('gif','png','jpg','jpeg', 'GIF','PNG','JPG','JPEG'), 'absolutePaths' => false));
        $data=array();
        if ($files) {
            if ($this->useFolders) {
                $baseDirData = array();
                $foldersData = array();

                foreach($files as $file) {
                    $baseDir = dirname($file);
                    if ($baseDir == '.')
                        $baseDir = '';
                    $dataItem=array(
                        'thumb'=>urlencode($attributeUrl.$file),
                        'image'=>urlencode($attributeUrl.$file),
                        'folder' => $baseDir,
                    );
                    if ($baseDir != '')
                        $foldersData[] = $dataItem;
                    else
                        $baseDirData[] = $dataItem;
                }
                $data = array_merge($baseDirData, $foldersData);
            } else {
                foreach($files as $file) {
                    $data[] = array(
                        'thumb'=>$attributeUrl.$file,
                        'image'=>$attributeUrl.$file,
                    );
                }
            }
        }
        //print_r($data);
        //Yii::app()->end();
        echo CJSON::encode($data);
        exit;
	}
}