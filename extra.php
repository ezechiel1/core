<?php
/**
 *
 */
class Extra
{

	function __construct()
	{
		# code...
	}

	public function ezpk($subject)
	{
	  $subject=trim($subject);//Remove blank space
	  $search=[' ','  ','   ','-',' -','~'];
	  $subject=str_replace($search, "_", $subject);//Replace inside listed chars  by '_'
	  $subject=strtolower($subject);//to lower  char
	  return $subject;
	}

	public function createParentFolder($parentFolder)
	{
		//code to create the Directory
        $current_path="../img/product/".$this->ezpk($parentFolder);
        if(!file_exists($current_path)){
             mkdir($current_path,0777,true);
        }
        return $current_path;
	}

	public function createFolder($parentFolder,$foldername)
	{
		//code to create the Directory
        $current_path="../img/product/".$this->ezpk($parentFolder).'/'.$this->ezpk($foldername);
        if(!file_exists($current_path)){
             mkdir($current_path,0777,true);
        }
	}

	public function renameParentFolder($currentPath,$parentFolder)
	{
		//code to Rename the Directory
        $old_path="../img/product/".$this->ezpk($currentPath);//Old Path
        $current_path="../img/product/".$this->ezpk($parentFolder);
        if(!file_exists($current_path)){
            rename($old_path, $current_path);//Rename the Directory
        }
	}

	public function deleteParentFolder($parentFolder)
	{
		//code to Delete the Directory
        $current_path="../img/product/".$this->ezpk($parentFolder);
        rmdir($current_path);//delete the Directory
	}

	public function uploadPicture($path,$fileName)
	{
		$name=$fileName['name'];
	    $ext=strrchr($name, '.');
        $tmp_name = $fileName['tmp_name'];
        $dir_picture = $path.$name;
        $valables = array('.jpg','.JPG','.PNG','.png','.jpeg','.JPEG');
		if(in_array($ext, $valables)):
                if(move_uploaded_file($tmp_name, $dir_picture)):
                  	return $name;
                else:
                 	return false;
                endif;
        else:
        	return false;
    	endif;
	}
	public function uploadFile($path,$fileName)
	{
		$name=$fileName['name'];
	    $ext=strrchr($name, '.');
        $tmp_name = $fileName['tmp_name'];
        $dir_picture = $path.$name;
        $valables = array('.jpg','.JPG','.PNG','.png','.JPEG','.jpeg','.docx','.doc','.xls','.xlsx','.pdf','.PDF');
		if(in_array($ext, $valables)):
                if(move_uploaded_file($tmp_name, $dir_picture)):
                  	return $name;
                else:
                 	return false;
                endif;
        else:
        	return false;
    	endif;
	}

	public function downloadFile($pathFile, $fileName='ezpk.jpg')
	{
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$fileName."");
			header("Content-Length: ".filesize($pathFile));
			readfile($pathFile);
	}

}


?>
