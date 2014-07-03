<?php include DOCUMENT_ROOT.'/view/inc/header.inc.php'; ?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">ePub3 eBook Maker</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      </ul>
      <ul class="nav navbar-nav">
        <li><a id="tour-trig" title="Click to run tour" href="#">Run Tour</a></li>
		<li><a data-target="#reading-info" data-toggle="modal" title="Click to learn how to read epub" href="#">Reading the eBooks</a></li>
		<li><a title="Survey" target="_blank" href="https://www.surveymonkey.com/s/8T9Z35T">User Survey</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
	<div class="row">   
		<div class="col-sm-12 main">
		<h1>ePub3 eBook Maker <small>Beta v0.2</small></h1>
			<?php if( !empty($data['error']) ): ?>            
				<div class="alert alert-danger fade in">
					  <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button>
					  <h4>You got an error!</h4>
					  <p><?php echo html_entity_decode($data['error']) ?> </p>
				</div>            
			<?php endif; ?>
				<div class="alert alert-warning fade in visible-xs">
					  <button title="close" aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button>
					  <h4>Notice to Mobile Users</h4>
					  <p>Please be aware that this application was not designed for use with mobile devices in mind, some features will not be accessible unless it is used on a desktop</p>
				</div>   	
				<div class="alert alert-info fade in visible-xs">
					  <button title="close" aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button>
					  <h4>Notice to Users</h4>
					  <p>This web application is best used via the most recent versions of Chrome, Firefox and Internet Explorer for <strong>Desktop</strong> computers</p>
				</div>  				
		</div>
	</div>    
      
	<div class="row text-center resize-src">	
		<div class="col-sm-4 col-xs-12 resize-target">
			<div class="panel panel-default">
				<div id="about-panel" class="panel-heading"><b>Create your own eBooks!</b></div>
				<div class="panel-body clearfix">
					<p>Epub is a format used for ebooks, and epub3 is the newest version as defined by the IDPF...</p>
					<button data-target="#more-info" data-toggle="modal" id="more-info-trig" class="btn btn-primary btn-md" title="Click for more info about app">
						More Info
					</button>
				</div>				
			</div>
		</div>  	
		<div class="col-sm-4 col-xs-12 resize-target">
			<div class="panel panel-default">
				<div id="controls-panel" class="panel-heading"><b>EPUB Controls</b></div> 
				<div class="panel-body clearfix app-controls">
					<p>Add a chapter to the pane below to enable these.</p>
					<br>
					<button type="button" id="epub-form-trig" class="btn btn-primary btn-md" title="Click to create your epub" data-toggle="modal" data-target="#epubForm" disabled="disabled">
						Create Epub
					</button>    
				   <button type="button" id="epub-data-clear" class="btn btn-primary btn-md" title ="Click to clear all chapters in pane" disabled="disabled">
						Clear Epub
					</button>                          
				</div>
			</div>            
		</div>   
		 <div class="col-sm-4 col-xs-12 resize-target">
			<div class="panel panel-default">
				<div id="chapter-panel" class="panel-heading"><b>Draggable Chapter</b></div>
				<div class="panel-body text-left">
					<div class="row">
						<div class="col-xs-6">
							<div class="element">
								<section>
									<div class="controls" contenteditable="false">
										<div class="btn-group">
											<button type="button" name="move-chapter" class="drag-handle btn btn-default btn-sm" title="Click and hold to drag blank chapter">
												<i class="fa fa-arrows"></i>
											</button>
											<button type="button" name="del-chapter" class="del-handle btn btn-default btn-sm" disabled="disabled" title="Click to delete chapter">
												<i class="fa fa-trash-o"></i>
											</button>
										</div>
									</div>
										<div contenteditable="true" class="chapter-title editable">
											<span>Chapter Title</span>
										</div>
										<section contenteditable="true" class="editable">
											<span>Chapter Body</span>
										</section>
								</section>                    
							</div>                              
						</div>						
						<div class="col-xs-6 text-center">Click & hold <i class="fa fa-arrows"></i> to drag a blank chapter to the pane below.</div>											
					</div>				
				</div>
			</div>
		</div>  		
	</div>
    
	<div class="row placeholders">
		<div class="col-xs-12 col-sm-12 placeholder">
			<div class="panel panel-default">
				<div class="panel-body">
					<div id="main-editor" class="editor contents">
					

					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row placeholders">
		<div class="col-xs-12 col-sm-12 text-center">
			<p class="lead">With thanks to the following people:</p>
		</div>	
		<div class="col-xs-12 col-sm-12 text-center">
			<p>Morgen Bailey of <a href="http://morgenbailey.wordpress.com/" title="MorgEn Bailey's Writing Blog">MorgEn Bailey's Writing Blog</a></p>
		</div>
		<div class="col-xs-12 col-sm-12 text-center">
			<p>Daniel Burrows of the <a href="http://ngbg.webs.com/aboutus.htm" title="Northampton Gay Book Group">Northampton Gay Book Group</a></p>
		</div>		
	</div>	
</div>
    


<!-- Modal -->
<form id="epub-data" enctype="multipart/form-data" class="form-horizontal" data-toggle="validator" role="form" name="epub-data" action="<?php echo URL; ?>/epub/create" method="POST">
<div class="modal fade" id="epubForm" tabindex="-1" role="dialog" aria-labelledby="epubFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button title="Close Modal" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="epubFormLabel">Epub/eBook Details</h4>
      </div>
      <div class="modal-body">
		  <div class="form-group">
			<label for="epubTitle" class="col-sm-4 control-label">Title</label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" id="epubTitle" name="epubTitle" placeholder="The Less Popular Adventures of the Mouldy Sock">
			</div>
		  </div>
		  <div class="form-group">
			<label for="epubAuthor" class="col-sm-4 control-label">Author</label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" id="epubAuthor" name="epubAuthor" placeholder="Joe Bloggs" >
			</div>
		  </div>
		  <div class="form-group">
			<label for="epubImage" class="col-sm-4 control-label">Cover Image:<br><small>(jpg only)</small></label>
			<div class="col-sm-8">
				<input name="epubImage"  id="epubImage" type="file" />         			              
			</div>        
		  </div>
      </div>
      <div class="modal-footer">
        <button title="Close Modal" type="button" class="btn btn-primary btn-md" data-dismiss="modal">Close</button>
		<button title="Create Epub" id="create-epub" type="submit" class="btn btn-primary btn-md">Create Epub</button>
      </div>
    </div>
  </div>
</div>
</form>

<div class="modal fade" id="epubFormSuccess" tabindex="-1" role="dialog" aria-labelledby="epubFormSuccessLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button title="Close Modal" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="epubFormSuccessLabel">Success</h4>
      </div>
      <div class="modal-body text-center">
			<p class="lead">
				Your download should start shortly.
			</p>	
			<p>
				Please take the time to complete this short survey.<br>
				<a title="Survey" target="_blank" href="https://www.surveymonkey.com/s/8T9Z35T">User Survey</a>
			</p>
      </div>
      <div class="modal-footer">
        <button title="Close Modal" type="button" class="btn btn-primary btn-md" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="more-info" tabindex="-1" role="dialog" aria-labelledby="more-infoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button title="Close Modal" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="more-infoLabel">About Epub3</h4>
      </div>
      <div class="modal-body text-left">
			<p>
				It uses the latest web technologies to render eBooks. This application is meant to allow anyone to easily create their own
				simple eBooks using the ePub3 standard without any technical knowledge.
			</p>
			<p>
				We recommend the use of the free Chromium web app Readium to read eBooks created here.
			</p>
			<p>	
				For more information click the <strong>'Reading the eBooks'</strong> link at the top of the page after closing this modal.
			</p>		
      </div>
      <div class="modal-footer">
        <button title="Close Modal" type="button" class="btn btn-primary btn-md" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reading-info" tabindex="-1" role="dialog" aria-labelledby="more-readingLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button title="Close Modal" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="more-readingLabel">How to Read Epub3 Files</h4>
      </div>
      <div class="modal-body text-left">
			<p class="lead">
				The recommended reader for the eBooks created with this application is Readium.  Follow the steps below to get it.				
			</p>		
			<ol>
				<li>If you do not already have the Chrome browser installed go to the Chrome website to download it, install it once the download is finished.</li>
				<li>Once the Chrome browser is installed you will need to install the Readium extension which can be done from this <a href="https://chrome.google.com/webstore/detail/readium/fepbnnnkkadjhjahcafoaglimekefifl" title="Link to Readium extension">page</a></li>
				<li>Click the 'Install' button.</li>
				<li>Once installed you can access the reader by navigating to the 'Apps' section of Chrome, the icon should be visible in the top right hand corner of the browser.</li>
				<li>Once Readium is open you can add new eBooks by clicking the <i class="fa fa-plus"></i> icon at the top right.</li>
				<li>When prompted select the option 'From Local File' and navigate your downloads folder. You should have a file with a name similar to the following '20140419154653770.epub'; select it and open it.</li>
				<li>The ebook should have been added to your library and you can view it simply by clicking on the image.</li>
			</ol>
      </div>
      <div class="modal-footer">
        <button title="Close Modal" type="button" class="btn btn-primary btn-md" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

	
<?php include DOCUMENT_ROOT.'/view/inc/footer.inc.php'; ?>	
