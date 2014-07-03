<?php  

class Util {
	//Pull Action from Global Arrays  
	public static function getAction() {    
		if(isset($_POST['action'])) 
			$action = htmlspecialchars($_POST['action']);            
		else if(isset($_GET['action']))         
			$action = htmlspecialchars($_GET['action']);            
        else
			$action = '';     
		return $action;
	} 

    /* Pull controller/action/param from clean url - requires htaccess mod_reqrite below:
     * Options +FollowSymLinks
     * RewriteEngine on
     * RewriteRule ^([a-zA-Z]*)/?([a-zA-Z]*)?/?([a-zA-Z0-9]*)?/?$ index.php?controller=$1&action=$2&param=$3 [NC,L]
     */
    public static function getUrlData() {
        parse_str($_SERVER['QUERY_STRING'], $vars);   
        $url = array (
            'controller'    => htmlspecialchars($vars['controller']),
            'action'        => htmlspecialchars($vars['action']),
            'param'         => htmlspecialchars($vars['param']),
        );        
       return $url;
    }    
	
	/**
	  * Function to recursively copy a folder and it's contents
	  * @auther Amanda Rose 2014
	  * $src STRING - should be the file path to the directory
	  * $dest STRING - should be the destination file path wher ethe $src will be copied
	  * $id DEFAULT = 0 - renamed the copied directory with a supplied if
	  * Usage Example:
	  *	$id = 0;
	  *	recursiveCopy('custom', "destination");
	  */
	public static function recursiveCopy($src, $dest, $id = 0) {

		$dirSep = '/';
		$dir = new DirectoryIterator( $src );

		
		if ( !file_exists($dest)):
			mkdir( $dest , 0777 );
        endif;

		if ( !file_exists($dest . $dirSep . $id)):
			mkdir( $dest . $dirSep . $id , 0777 );
		 endif;
		
		foreach ($dir as $focus) :
			if ( $focus->isDir() && !$focus->isDot()):
				try {
				if ( !file_exists($dest . $dirSep. $id . $dirSep . strstr($dirSep, $focus->getPath()) ) && file_exists($focus->getPath()) ) 
					mkdir( $dest . $dirSep. $id . $dirSep . strstr($dirSep, $focus->getPath()) , 0777 );
				Util::recursiveCopy( $focus->getPathName(), $dest . $dirSep. $id . $dirSep . $focus->getFilename(), false );
				} catch (Exception $e) {
					echo 'Exception: ',  $e->getMessage(), "\n";
				}
			endif;    
			if ( $focus->isFile() && !$focus->isDot() ):
				try {
					copy( $focus->getPathName(),  $dest . $dirSep. $id . $dirSep . $focus->getFilename() );
				} catch (Exception $e) {
					echo 'Exception: ',  $e->getMessage(), "\n";
				}
			endif;
		endforeach;        
	}	
	

    public static function getRandomNumberString( $length ) {
        $result = "";        
        for($i=0; $i<$length; $i++) {
            $result .= mt_rand(0, 9);
        }        
        return $result;    
    }
	
 	public static function load($page=null, $data=Null) {
		include $page;
	}   
    
    public static function delFile( $filepath ) {
        if( file_exists($filepath) && is_writable($filepath) ):
            unlink($filepath);
            return true;
        else:
            return false;
        endif;    
    }

}