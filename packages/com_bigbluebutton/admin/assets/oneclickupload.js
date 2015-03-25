
/**
 * description: provides a one click upload
 *              
 * license:		GNU/GPL
 * author: 		Michael Liebler, www.janguo.de
 * copyright:	Copyright (C) 2010 Open Source Matters. All rights reserved.
 * 
 * Example:
 * new JSOneClickUpload('my_upload_button_id', {					
					url: 'upload.php', // url to your upload script
					uploadFieldName: 'image', // name of file field, to be found by your upload script					 												
					// callback functon  after upload
					onComplete: function(response){
						// response may be: {"imagepath":"\/images\/myimages\/userimage.jpg", "title":"Unknown"}
						var obj = JSON.parse(response);
						document.getElementById('preview').src = obj.imagepath;
					}
				});
 * 
 * How to use:
 * 1. Create an upload script, that may return the path to the uploaded image as a json object like  {"imagepath":"\/images\/myimages\/userimage.jpg", "title":"Unknown"}
 * 2. Provide an upload button, e.g. <button type="button" id="my_upload_button_id">Upload</button>
 *    First parameter of JSOneClickUpload is the id of your button
 * 3. When the user clicks on the button, he will select a local image which will immediately be sent to your script
 * 4. The onComplete function may show the user the image by replacing a dummy image <img id="preview" src="/images/noimage.png" />     
 */

var JSOneClickUpload = (function(clickbutton, options) {	
	
	var Uid = Date.now();
	
	// Default vars
	var defaults = {
		onComplete: function () {},
		onRequest: function () {},
		onError: function () {},
		url: '/',
		method: 'post',
		uploadFieldName: 'image',
		id: 'irequest' + (Uid++).toString(36),
		token:null, // For Joomla: token:'<?php echo JSession::getFormToken() ?>',
		postvars: {}		
	}

	// Merge options to this
	for (var k in defaults) {
        this[k] = options.hasOwnProperty(k) ? options[k] : defaults[k];
    }
	
	var self = this;
	
	// init function
	this.init = function() {
		
		this.clickbutton = document.getElementById(clickbutton);
		this.loading = false;	
		this.frameId = 'iframe_'+this.id;		
		
		// prepare the form
		this.buildParentForm(); 	

		// prepare the frame
		this.buildIframe();		
		
		// bind click to upload button
		this.clickbutton.onclick = function (e)  { self.uploadField.click(); };	
		
		// user has selected an image: submit the form
		this.uploadField.onchange = function (e) {self.send(); };			
	
	}
	
	this.buildParentForm = function() {
		this.parentForm = document.createElement('form');
		this.parentForm.setAttribute('id',this.id +'uploadForm');
		this.parentForm.setAttribute('style','display:none');
		this.parentForm.setAttribute('action',this.url);
		this.parentForm.setAttribute('method',this.method);
		this.parentForm.setAttribute('enctype','multipart/form-data');
		this.uploadField  = document.createElement('input');
		this.uploadField.type = "file"
		this.uploadField.name = this.uploadFieldName;
		this.uploadField.setAttribute('id',this.id +'uploadField');			
		this.parentForm.appendChild(this.uploadField);
		for(var key in this.postvars) {
			var hidden = document.createElement('input');
			hidden.type = "hidden";
			hidden.name = key;
			hidden.value = this.postvars[key];
			hidden.setAttribute('id',this.id + key);
			this.parentForm.appendChild(hidden);
		}
		
		if (this.token) {
			var hidden = document.createElement('input');
			hidden.type = "hidden";
			hidden.name = this.token;
			hidden.value = 1;
			this.parentForm.appendChild(hidden);
		}
		document.body.appendChild(this.parentForm);
		// forms target is the iframe
		this.parentForm.setAttribute('target', this.frameId);
		
	}
	
	// Build iframe
	this.buildIframe = function() {
		this.IFrame = document.createElement('iframe');
		this.IFrame.name = self.frameId;
		this.IFrame.setAttribute('id',this.frameId);
		this.IFrame.style.display = 'none';
		this.IFrame.setAttribute('src','about:blank');
		this.IFrame.addEventListener('load', function(e) {			
			e.preventDefault();
			// prevent execution on first load
			if (self.loading){
				// get content for onComplete callback
				var doc = self.IFrame.contentWindow.document;
				if (doc && doc.location.href != 'about:blank'){
					self.onComplete(doc.body.innerHTML); 
				} else {
					self.onError('Error on upload')
				}
				self.loading = false;
			}
		}, false);
		// append iframe to body
		document.body.appendChild(this.IFrame);
	}
	
	// the send method
	this.send =function(){
		self.onRequest();		
		self.loading = true;
		this.parentForm.submit();
	}
	// initialize when document is ready
    var docLoaded = setInterval(function () {
		if(document.readyState === 'complete') {
    		clearInterval(docLoaded);        						
    		self.init();			
		  }
	}, 100);   		
});
