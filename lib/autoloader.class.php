<?php
/** Auto Loader Class **
 *  @author Amanda Rose 2014
 */

class Autoloader {
    # Static array in format 'filepath' => 'directory name, identifier'
    private static $paths = array(        
        '/lib/'                   => '.class.php',
        '/app/'                   => '.class.php'
    );

    public static function AllLoader($inputClass) 
	{
        $myClass = strtolower($inputClass); // Because class file names should be lower case while class names are upper
       
        if (class_exists($myClass) != true): 
            foreach(Autoloader::$paths as $key => $value):                
                if (is_file(DOCUMENT_ROOT.$key.$myClass.$value)):                         
                        include(DOCUMENT_ROOT.$key.$myClass.$value);
                        break;
                endif;
            endforeach; 
        endif;
    }
		
}
