<?php

class ImportCommand extends CConsoleCommand
{

    public function actionBusinessCenters($csvFile, $encoding = 'utf-8', $delimiter = ';', $enclosure = '"', $idColumn = 1, $captionColumn = 2, $addressColumn = 3, $phoneColumn = 4, $skipFirstRow = "true", $idAssignmentPhpFile = null)
    {
        if (file_exists($csvFile)) {
            $inputFile = $csvFile;
            if ($encoding != 'utf-8') {
                $inputFile = Yii::getPathOfAlias('application.runtime').DIRECTORY_SEPARATOR.'import_bc_temp.csv';
                $file = file_get_contents($csvFile);
                $file = iconv($encoding, "utf-8", $file);
                file_put_contents($inputFile, $file);
                unset($file);
            }

            $idColumn--;
            $captionColumn--;
            $phoneColumn--;
            $addressColumn--;


            $file = fopen($inputFile, 'r');
            if ($file) {
                if ($skipFirstRow === true || $skipFirstRow == 'true')
                    $skipFirstRow = true;
                else
                    $skipFirstRow = false;

                $idAssignment = array();
                if ($idAssignmentPhpFile && file_exists($idAssignmentPhpFile)) {
                    $idAssignment = require($idAssignmentPhpFile);
                }


                $n = 0;
                while ($line = fgetcsv($file, null, $delimiter, $enclosure)) {
                    $n++;
                    if ($n == 1 && $skipFirstRow)
                        continue;

                    if (is_array($line)) {
                        if (isset($line[$idColumn]) && isset($line[$captionColumn]) && isset($line[$addressColumn]) && isset($line[$phoneColumn])) {
                            $businessCenter = null;
                            if (isset($idAssignment[$line[$idColumn]]))
                                $businessCenter = BusinessCenter::model()->findByPk($idAssignment[$line[$idColumn]]);
                            if (!$businessCenter)
                                $businessCenter = new BusinessCenter();

                            $businessCenter->caption = $line[$captionColumn];
                            $businessCenter->address = $line[$addressColumn];
                            $businessCenter->phone = $line[$phoneColumn];
                            if ($businessCenter->save(false)) {
                                $idAssignment[$line[$idColumn]] = $businessCenter->id;
                            }
                        } else {
                            echo "\n".'Some columns does not exists on line '.$n;
                            break;
                        }
                    }
                }

                if (count($idAssignment) && $idAssignmentPhpFile) {
                    $assignmentFile = var_export($idAssignment, true);
                    $assignmentFile = "<?php\n\nreturn " . $assignmentFile . ";";
                    file_put_contents($idAssignmentPhpFile, $assignmentFile);
                }

                fclose($file);

                if ($csvFile != $inputFile)
                    @unlink($inputFile);
            } else
                echo "\n".'Could not open input file';
        } else
            echo "\n".'File '.$csvFile.' not found.';
        echo "\n";
    }

}