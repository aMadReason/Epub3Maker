<?php

/**
 *  Main class handles main application
 */
class Main {
	// properties
	private $url;
	private $uid;
	private $error;
	private $chapters = array();
	private $meta = array();
	private $coverImage = array();
	
	/**
	 *  Main class constructor.
	 *   Acts as a single point of entry between the front-end and the application
	 *   It determines what actions will be carried out base don url values within the constructor
	 *   as well as checking and sanitizing user input for use within the rest of the application.
	 *  
	 *  @param [in] $url Array containing url data 
	 *  
	 */
	public function __construct( $url ) {		
		$this->url = $url;
		// generates a unique identifier used throughout the application
		$this->uid = ( new DateTime('now', new DateTimeZone('Europe/London')) )->format('YmdHis') . Util::getRandomNumberString(3);				
		
		// nested switch to determine what actions should be carried out 
		switch($this->url['controller']):
			case 'epub':               
                switch($this->url['action']):
                    case 'create': 
                        $this->prepareData();
                        if( empty($this->error) == true): // if no errors
							// Instantiates the Epub class triggering the rest of the processing.
                            $page = new EPUB( $this->uid , $this->chapters, $this->meta, $this->coverImage);
                            header('Location: '.URL.'/epub/pull/'. $this->uid);			
                        else:
                            $_SESSION['error'] = $this->error;
                            header('Location: '.URL);							
                        endif;
                    break;
                    case 'pull': 
                        if( !file_exists(DOCUMENT_ROOT.'/app/epub-holder/'.$this->url['param'].'.epub') ):
                            $this->error .= 'The EPUB you are trying to access doesn\'t exist';
                            $_SESSION['error'] = $this->error;
                            header('Location: '.URL);							
                        else:
                            $file = DOCUMENT_ROOT.'/app/epub-holder/'.$this->url['param'].'.epub';	

							//serves the epub file to the user
                            EPUB::serveEPUB( $file );
							
							// Deletes created epub after it has been served to the user
							Util::delFile( $file);
							
							// clears $_POST of existing values
							unset($_POST);
							$_POST = array();							
                        endif;
                    break;	
					default:
						if ( isset($_SESSION['error']) ): 
							// if errors saves in session pass to view on page load
							$data = array('error'=> htmlentities($_SESSION['error']) );
							 $_SESSION['error'] = '';
							Util::load('view/index.html.php', $data);
						else:
							// if no errors just load page
							Util::load('view/index.html.php');
						endif;
					break;
                 endswitch; // end action switch
			break;
			default: //Home Page   
                if ( isset($_SESSION['error']) ): 
                    // if errors saves in session pass to view on page load
                    $data = array('error'=> htmlentities($_SESSION['error']) );
                     $_SESSION['error'] = '';
                    Util::load('view/index.html.php', $data);
                else:
                    // if no errors just load page
                    Util::load('view/index.html.php');
                endif;
			break;
		endswitch; // end controller switch
	} #end constructor
	
	/**
	 *  Method that sanitizes HTML input for chapters before adding it to chapters array
	 *  
	 *  @param [in] $chapterArr Array containing chapter data
	 *  
	 */
	private function processChapterInput($chapterArr) {
		$counter = 1; // chapter count starts at 1
        
        // configure HTMLpurifier
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'XHTML 1.1');
        $config->set('HTML.Allowed', 'ul,ol, li,p[style],b[style],i[style],span[style],br[style],strong[style],em[style],h1[style],h2[style],h3[style],h4[style],h5[style],h6[style]');         
        
		foreach( $_POST['chapters'] as $value ):
            $cleanedValue = (new HTMLPurifier($config))->purify( $value ); // run HTMLpurifier against chapter  			
			$this->chapters[ "Chapter " . $counter  ] = htmlspecialchars( $cleanedValue ); //creates 'Chapter 1' output
			$counter++; // increment chapter counter
		endforeach;
	} // end function
	
	/**
	 *  Method for processing custom cover image uploaded by user
	 *  
	 *  @param [in] $coverImageArr Array of image data
	 *  
	 */
	private function processCoverInput( $coverImageArr ) {
		$this->coverImage['name'] 			= $coverImageArr['name'];
		$this->coverImage['type'] 				= $coverImageArr['type'];
		$this->coverImage['temp_location'] = $coverImageArr['tmp_name'];
		$this->coverImage['size'] 				= $coverImageArr['size'];
		$this->coverImage['error'] 			= $coverImageArr['error'];
		$this->coverImage['default'] 			= false;
		
		// allowed img extensions
		$extensions = array("jpeg","jpg");   
		
		// get the extension of uploaded file
		$fileExtension = strtolower(pathinfo($this->coverImage['name'], PATHINFO_EXTENSION));  
                                
		if( in_array( $fileExtension, $extensions ) == false):
			$this->error .= "Cover image must be a jpeg \n";
			echo $fileExtension;
			//print_r($extensions);
		elseif($this->coverImage['size'] > 2097152):
			$this->error .= 'File size must be less tham 2 MB \n';
		endif;				
		
		if(empty( $this->error ) === true):
			move_uploaded_file($this->coverImage['temp_location'], DOCUMENT_ROOT . "/app/img_tmp/". $this->uid .'.'.$fileExtension);
			$this->coverImage['temp_location'] = DOCUMENT_ROOT . "/app/img_tmp/". $this->uid .'.'.$fileExtension;
		else:
			return false;
		endif;	
	} // end function
	
	/**
	 *  Method for handling default image should the user not upload a custom image.
	 *  
	 *  @param [in] $name Image name
	 *  @param [in] $location Location of default image
	 *  
	 */
	private function useDefaultCover( $name, $location) {
		$this->coverImage['name'] 				= $name;
		$this->coverImage['temp_location'] 	= $location;
		$this->coverImage['default'] 				= true;
	}
    
	/**
     *  Method for checking and sanitizing user input 
     *  
     */
    private function prepareData() {
        $content = true;        
        $_SESSION['error'] = '';

        //check input is populated                       
        if( empty($_POST['chapters']) === true ):
            $this->error .= "Please ensure content has been added <br>";
            $content = false;
        endif;
        if( empty($_POST['epubTitle']) === true ):
            $this->error .= "Please ensure title has been added <br>";
            $content = false;
        endif;
        if( empty($_POST['epubAuthor']) === true ):
            $this->error .= "Please ensure an author has been added <br>";
            $content = false;
        endif;
        if( !file_exists($_FILES['epubImage']['tmp_name'] )): // checks file has been uploaded                             
            // allows for default image
        endif;

        // prepare content for processing
        if( $content === true):
            if( isset($_SESSION['error']) ): // if no error clear session error
                $_SESSION['error'] = '';
            endif;

			if( isset($_POST['chapters']) ):
				$this->processChapterInput($_POST['chapters']);
			endif;
			if( isset($_POST['epubTitle']) ):
				$this->meta['epubTitle'] = htmlspecialchars( strip_tags($_POST['epubTitle']) );
			endif;
			if( isset($_POST['epubAuthor']) ):
				$this->meta['epubAuthor'] = htmlspecialchars( strip_tags($_POST['epubAuthor'] ));
			endif;
			if( is_uploaded_file($_FILES['epubImage']['tmp_name']) ):	
				//if image uploaded process
				$this->processCoverInput( $_FILES['epubImage']);
			else:
				//if no image uploaded use default cover
				$this->useDefaultCover('cover', DOCUMENT_ROOT . "/app/img_tmp/cover.jpg");
			endif;
				
        endif;   
    } // end function
	
}  //end class



