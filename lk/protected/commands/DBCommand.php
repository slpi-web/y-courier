<?php


class DBCommand extends CConsoleCommand
{

    public function actionDump()
    {
        $filename = 'db_'.date('Y-m-d_H-i').'.sql.gz';
        $path = 'uploads' . DIRECTORY_SEPARATOR . $filename;
        $fullPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $path;

        $dbName = '';
        $connectionString = explode(';', Yii::app()->db->connectionString);
        $findStr = 'dbname=';
        foreach ($connectionString as $string) {
            if (strncmp($findStr, $string, mb_strlen($findStr)) === 0) {
                $dbName = mb_substr($string, mb_strlen($findStr));
            }
        }
        
        @exec('mysqldump -u'.Yii::app()->db->username.' -p'.Yii::app()->db->password.' '.$dbName.' | gzip > "'.$fullPath.'"', $log);
        echo "\n".'Dump saved to file: '.$path.'.'."\n\n";
    }

}