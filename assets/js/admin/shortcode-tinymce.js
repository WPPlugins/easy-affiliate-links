(function() {
    tinymce.PluginManager.add('easyaffiliatelinks', function( editor, url ) {
        function replaceShortcodes( content ) {
            var shortcode_id = 0;
            return content.replace( /\[eafl([^\]]*)\]/g, function( match ) {
                shortcode_id++;
                return html( match, shortcode_id );
            });
        }

        function html( data, shortcode_id ) {
            var id = data.match(/id="?'?(\d+)/i);
            var text = data.match(/text="([^"]+)/i);
            var name = data.match(/name="([^"]+)/i);

            text = text == null ? 'affiliate link' : text[1];
            name = name == null ? '' : name[1];

            data = window.encodeURIComponent( data );
            return '<span style="border-bottom: 1px dashed #2980b9; cursor: pointer;" ' +
                'data-eafl-id="' + id[1] + '" data-eafl-text="' + text + '" data-eafl-name="' + name + '" data-eafl-shortcode-id="' + shortcode_id + '" data-eafl-shortcode="' + data + '" contentEditable="false">' + text + '</span>';
        }

        function restoreShortcodes( content ) {
            function getAttr( str, name ) {
                name = new RegExp( name + '=\"([^\"]+)\"' ).exec( str );
                return name ? window.decodeURIComponent( name[1] ) : '';
            }

            return content.replace( /(<span [^>]+>[^<]*<\/span>)/g, function( match, elem ) {
                var data = getAttr( elem, 'data-eafl-shortcode' );

                if ( data ) {
                    return data;
                }

                return match;
            });
        }

        editor.on( 'mouseup', function( event ) {
            var dom = editor.dom,
                node = event.target;

            if ( node.nodeName === 'SPAN' && dom.getAttrib( node, 'data-eafl-shortcode' ) ) {
                // Don't trigger on right-click
                if ( event.button !== 2 ) {
                    var id = dom.getAttrib( node, 'data-eafl-id' );
                    var text = dom.getAttrib( node, 'data-eafl-text' );
                    var name = dom.getAttrib( node, 'data-eafl-name' );
                    var shortcode_id = dom.getAttrib( node, 'data-eafl-shortcode-id' );

                    eafl_admin.open_modal(editor.id, {
                        link_id: id,
                        shortcode_id: shortcode_id,
                        selection: text,
                        name: name
                    });
                }
            }
        });

        editor.on( 'BeforeSetContent', function( event ) {
            event.content = replaceShortcodes( event.content );
        });

        editor.on( 'PostProcess', function( event ) {
            if ( event.get ) {
                event.content = restoreShortcodes( event.content );
            }
        });
    });
})();