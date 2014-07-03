<?php
/**
 *  EPUB class is the class that handles the primary application operations as far as the creation of the epub file is concerned
 */
class EPUB {
    // properties
	private $uid = '';
	private $chapterArr = array();
    private $navArr = array();
	private $metaArr = array();
	private $coverImageArr = array();
	private $zip;

   /**
     *  Class constructor
     *  
     *  @param [in] $uid Parameter_Description
     *  @param [in] $chapterArr Array of chapters to be processed
     *  @param [in] $metaArr Array of meta data including the author name and ebook title
     *  @param [in] $coverImageArr Array of image data as passed by Main
	 *
     */
    public function __construct( $uid, $chapterArr, $metaArr, $coverImageArr) {
		$this->uid = $uid;
		$this->chapterArr = $chapterArr;
		$this->metaArr = $metaArr;
		$this->coverImageArr = $coverImageArr;
		
		// copies the zip template into the epub-holder dir for processing
		copy(DOCUMENT_ROOT.'/app/epub-template/epub_template.zip', DOCUMENT_ROOT.'/app/epub-holder/'.$this->uid.'.zip');
		
		// instatiates the ZipArchive class and populates it with the newly copied file
		$this->zip = new ZipArchive();
		$this->zip->open( DOCUMENT_ROOT.'/app/epub-holder/'.$this->uid.'.zip' );	
		
		// creation methods
		$this->createChapters();		
        $this->createNav();
		$this->createCover();
		$this->createPackage();
		
		// closes the zip file completing the associated actions as determines by the creation methods above
		$this->zip->close();
		
		// change the .zip file to .epub
		rename(DOCUMENT_ROOT.'/app/epub-holder/'.$this->uid.'.zip', DOCUMENT_ROOT.'/app/epub-holder/'.$this->uid.'.epub');
		
		// iremoves any custome cover images store don the server
		if( $this->coverImageArr['default'] !== true ):			
			Util::delFile( DOCUMENT_ROOT . "/app/img_tmp/". $this->uid .'.jpg' );
		endif;	
    }
	
	/**
	 *  Method for instantiate a chapter object for each item in the chapterArr
	 */
	private function createChapters(  ) {
		$counter = 1;
		foreach($this->chapterArr as $title => $chapter):
			$chap = new Chapter($this->zip, $this->uid, $title, $chapter, "chapter-".$counter);
            $this->navArr[ "chapter-".$counter ] = $title; // adds title and chapter to nav array for creating nav file
			$counter++;
		endforeach;
	}
	
	/**
	 *  Method to instantiate the the Cover class
	 */
	private function createCover() {
		$cover = (new Cover($this->zip, $this->uid, $this->coverImageArr));
	}
    
	/**
	 *  Method to instantiate the the Nav class
	 */	
    private function createNav() {
        $nav = new Nav($this->zip, $this->uid, $this->navArr, $this->metaArr['epubTitle']);
    }
	
	/**
	 *  Method to instantiate the the Package class
	 */	
    private function createPackage() {
        $package = new Package($this->zip, $this->uid, $this->navArr, $this->metaArr);
    }	
    
	/**
	 *  Method to serve the created epub file
	 */
	public static function serveEPUB( $epubFile ) {
        // Sourced from http://stackoverflow.com/questions/2088267/download-of-zip-file-runs-a-corrupted-file-php - Gumbo
        if (headers_sent()):
            echo 'HTTP header already sent';
        else:
            if (!is_file($epubFile)) {
                header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
                echo 'File not found<br>';
            } else if (!is_readable($epubFile)) {
                header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
                echo 'File not readable';
            } else {
                header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
                header("Content-Type: application/epub+zip");
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: ".filesize($epubFile));
                header("Content-Disposition: attachment; filename=\"".basename($epubFile)."\"");
                readfile($epubFile);
            }
        endif;
	} 


}