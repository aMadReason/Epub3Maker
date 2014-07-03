<?php

/**
 *  Chapter class used to format input data into chapters and add them to the epub zip file
 */
class Chapter extends Element {
	// Properties
	private $title = null;
	private $zip;	
	
	/**
	 *  Chapter class constructor
	 *  
	 *  @param [in] $zip ZipArchive object as created in Main class
	 *  @param [in] $uid Unique identifier used throughout app to target active epub file
	 *  @param [in] $title Title of chapter
	 *  @param [in] $content Content of chapter
	 *  @param [in] $filename Name of chapter file
	 *  
	 */
	public function __construct( ZipArchive $zip, $uid, $title, $content, $filename ) {
		$this->zip = $zip;
		$this->uid = $uid;
		$this->title = $title;
		$this->content = $content;
		$this->filename = $filename;
		
		$this->populateTemplate();
		$this->createFile( $this->zip );
	}
	
	/**
	 * Method that generates template string before the file is created
	 */
	private function populateTemplate() {
	$this->template = <<<EOT
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="en" lang="en">
	<head>
		<title>{$this->title}</title>
		<link rel="stylesheet" type="text/css" href="css/epub-spec.css" />
	</head>
	<body class="reflow">
		{$this->content}
	</body>
</html>
EOT;
	}
}