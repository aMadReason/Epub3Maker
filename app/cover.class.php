<?php
/**
 *  Cover class  used to add both the cover image and file to the active zip file
 */
class Cover extends Element {
    //properties
    protected $filename = 'cover';
    private $coverImageArr = array();
	private $zip;
	
	/**
	 *  Method that initializes the cover class
	 *  
	 *  @param [in] $zip ZipArchive object as created in Main class
	 *  @param [in] $uid Unique identifier used throughout app to target active epub file
	 *  @param [in] $coverImageArr Array of image info
	 *  
	 */
	public function __construct(ZipArchive $zip, $uid, $coverImageArr) {
		$this->zip = $zip;
		$this->uid = $uid;
        $this->coverImageArr = $coverImageArr;
		
		$this->addCoverImage();
		$this->populateTemplate();
		$this->createFile( $this->zip );		
	}
    
	/**
	 *  Method to add cover image to active zip file
	 */	
	private function addCoverImage() {
			$this->zip->addFile($this->coverImageArr['temp_location'], 'EPUB/covers/'. $this->filename.'.jpg');
	}
	
	
	/**
	 * Method that generates template string before the file is created
	 */
	private function populateTemplate() {
	$this->template = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" lang="en" xml:lang="en">
	<head>
		<title>Cover</title>
		<link rel="stylesheet" type="text/css" href="css/epub-spec.css" />
	</head>
	<body class="reflow">
		<img src="covers/{$this->filename}.jpg" alt="Cover Image" title="Cover Image" />
	</body>
</html>

EOT;
	}
}