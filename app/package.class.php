<?php
/**
 *  Package class  used to create the package of the active zip file
 */
class Package extends Element {
    //properties
    protected $filename = 'package';
	private $fileArr = array();
	private $manifest = "";
	private $spine = "";
	private $dateMod = "";
	private $meta = array();
	private $zip;

	/**
	 *  Class constructor
	 *  
	 *  @param [in] $zip ZipArchive object as created in Main class
	 *  @param [in] $uid Unique identifier used throughout app to target active epub file
	 *  @param [in] $arr Array containing chapter data for use in rebuilding file array
	 *  @param [in] $metaArr Array containing epub title and author data
	 *  
	 */
	public function __construct(ZipArchive $zip, $uid, $arr, $metaArr ) {
		$this->zip = $zip;
		$this->uid = $uid;
		$this->meta = $metaArr;		
		
		// gets current date and formats it in epub compliant format
		$date = new DateTime('now', new DateTimeZone('Europe/London'));		
		$this->dateMod = $date->format('Y-m-d') . 'T' . $date->format('h:i:s') . 'Z';
		
		$this->rebuildFileArr( $arr ); // populates $fileArr
		$this->createManifest();
		$this->createSpine();
		$this->populateTemplate();
		$this->createFile( $this-> zip );
	}
	
	/**
	 *  Method for creating a file array of created chapters
	 */
	private function rebuildFileArr( $arr ) {
		$counter = 1;
        foreach( $arr as $file => $title ):
			$id = 'chp-' . $this->uid .'-'. $counter;
            $this->fileArr[ $id ] = $file;
			$counter++;
        endforeach;		
	}
	
	/**
	 *  Method to create the file, overwriting inherited method from elment
	 */
	protected function createFile( ZipArchive $zip) {
		$zip->addFromString('EPUB/'.$this->filename . ".opf", htmlspecialchars_decode($this->template));
	}

	/**
	 *  Method for creating manifest string
	 */
	private function createManifest () {
        foreach( $this->fileArr as $id => $file ):
            $this->manifest .= '<item id="'. $id .'" href="'. $file .'.xhtml" media-type="application/xhtml+xml"/>' . "\n";
        endforeach;	
	}
	
	/**
	 *  Method for creating spine string
	 */
	private function createSpine() {
        foreach( $this->fileArr as $id => $file ):
            $this->spine .= '<itemref idref="'. $id .'"/>' . "\n";
        endforeach;		
	}

	/**
	 * Method that generates template string before the file is created
	 */	
	private function populateTemplate() {
	$this->template = <<<EOT
<?xml version="1.0" encoding="utf-8" standalone="no"?>
<package xmlns="http://www.idpf.org/2007/opf" xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:dcterms="http://purl.org/dc/terms/" version="3.0" xml:lang="en"
	unique-identifier="uid">
	<metadata>
      <dc:identifier id="uid">{$this->uid}</dc:identifier>
      <dc:title>{$this->meta['epubTitle']}</dc:title>
      <dc:creator>{$this->meta['epubAuthor']}</dc:creator>
      <dc:language>en</dc:language>
      <meta property="dcterms:modified">{$this->dateMod}</meta>
	  <meta name="cover" content="cover-image"/>	  
	</metadata>
	<manifest>
		<item id="toc-nav" properties="nav" media-type="application/xhtml+xml" href="nav.xhtml"/>
		<item id="css" media-type="text/css" href="css/epub-spec.css"/>
		<item id="cover" href="cover.xhtml" media-type="application/xhtml+xml"/>
		<item id="cover-image" properties="cover-image" href="covers/cover.jpg" media-type="image/jpeg"/>
{$this->manifest}
	</manifest>
	<spine>   
		<itemref idref="cover" linear="no"/>
		<itemref idref="toc-nav" linear="yes"/>	
{$this->spine}
	</spine>
</package>
EOT;
	}
}