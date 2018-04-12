(function( tinymce ) {
	'use strict';

	tinymce.create( 'tinymce.plugins.caniuse_plugin', {

		capitalize: function( str ) {
			return str.charAt(0).toUpperCase() + str.slice(1);
		},

		sort_by: function( field, primer ){
			var key = primer ?
				function(x) {return primer(x[field])} :
				function(x) {return x[field]};

			return function (a, b) {
				return a = key(a), b = key(b), 1 * ((a > b) - (b > a));
			};
		},

		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function( ed, url ) {

			var features = [];

			$.getJSON('https://raw.githubusercontent.com/Fyrd/caniuse/master/fulldata-json/data-2.0.json', function(res) {

				//var featuresArray = [];
				for (var feature in res.data) {
					var featureTitle = res.data[feature].title;
					featureTitle = ed.plugins.caniuse_plugin.capitalize( featureTitle );

					feature = {
						value: feature,
						text: featureTitle
					};
					features.push(feature);
				}


				features.sort( ed.plugins.caniuse_plugin.sort_by( 'text', function(a){return a}));

			});

			ed.addButton( 'caniuse_button', {
				//title : 'Вставка шорткода Can I use',
				tooltip : 'Вставка шорткода Can I use',
				cmd : 'caniuse_command',
				//icon: 'dashicons-chart-area'
				image : url + '/icons/caniuse.png'
				//text : 'Can I use',
			});

			ed.addCommand( 'caniuse_command', function() {
				//var selected_text = ed.selection.getContent();
//				var number = prompt("How many posts you want to show ? "),
//					shortcode;
//				if (number !== null) {
//					number = parseInt(number);
//					if (number > 0 && number <= 20) {
//						shortcode = '[recent-post number="' + number + '"/]';
//						ed.execCommand('mceInsertContent', 0, shortcode);
//					}
//					else {
//						alert("The number value is invalid. It should be from 0 to 20.");
//					}
//				}

				ed.windowManager.open({
					title: 'Настройка шорткода Can I use',
					width: 400,
					height: 80,
					body: [
						{type: 'listbox', name: 'features', label: 'Фича',
							values : features,
							value : 'test2'},
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						ed.insertContent( '[caniuse feature="' + e.data.features + '"]' );
					},
					buttons_: [{
						text: 'Close',
						onclick: 'close'
					}]
				});
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'CanIUse Buttons',
				author : 'mihdan',
				authorurl : 'https://www.kobzarev.com/',
				infourl : 'https://www.kobzarev.com/',
				version : "0.1"
			};
		}
	});

	tinymce.PluginManager.add( 'caniuse_plugin', tinymce.plugins.caniuse_plugin );

})( window.tinymce );

// eof;