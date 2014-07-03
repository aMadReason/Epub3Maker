<?php
/**
 *  Nav class  used to add nav file to active zip
 */
class Nav extends Element {
	// properties
    protected $filename = 'nav';
    private $navArr = array();
	private $epubTitle = "";
	private $zip;

	/**
	 *  Method that initializes the nav class
	 *  
	 *  @param [in] $zip ZipArchive object as created in Main class
	 *  @param [in] $uid Unique identifier used throughout app to target active epub file
	 *  @param [in] $navArr Array containing nav array based on created chapters
	 *  @param [in] $title Name of epub
	 *  
	 */	
	public function __construct(ZipArchive $zip, $uid, $navArr, $title=false ) {
		$this->zip = $zip;
		$this->uid = $uid;
        $this->navArr = $navArr;
		$this->epubTitle = $title;
		
        $this->createContent();
		$this->populateTemplate();
		$this->createFile( $this->zip );
	}
    
	/**
	 *  Method to create content
	 */
    private function createContent() {
        foreach($this->navArr as $file => $title):
            $this->content .= '<li><a href="'.strip_tags($file).'.xhtml">'.strip_tags($title).'</a></li>'. "\n";
        endforeach;
    }

	/**
	 * Method that generates template string before the file is created
	 */	
	private function populateTemplate() {
	$this->template = <<<EOT
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="en"
	lang="en">
	<head>
		<title>{$this->epubTitle}</title>
		<link rel="stylesheet" type="text/css" href="css/epub-spec.css" />
	</head>
	<body class="reflow">
		<h1>{$this->epubTitle}</h1>
		<nav epub:type="toc" id="toc">
			<h2>Table of Contents</h2>
			<ol class="list-unstyled">
{$this->content}
			</ol>
		</nav>
	</body>
</html>
EOT;
	}
}