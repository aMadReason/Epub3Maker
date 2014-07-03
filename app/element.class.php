<?php
/**
 *  Abstract class intended to ensure all extending classes have the uid variable
 */
abstract class Element {
	protected $uid = null;
	protected $filename = null;
	protected $template = null;
	protected $content = null;
	
	/**
	 *  Method to add generated template to a file within the active zip file
	 */    
	protected function createFile(ZipArchive $zip) {
		$zip->addFromString('EPUB/'.$this->filename . ".xhtml", htmlspecialchars_decode($this->template));
	}    
}