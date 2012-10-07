(function() {
    tinymce.create('tinymce.plugins.Showcase', {
        createControl : function(n, cm) {
            switch (n) {
                case 'showcase':
                    var mlb = cm.createListBox('showcase', {
                        title : 'Showcase',
                        onselect : function(v) {
                            if(v != ''){
                                tinyMCE.execCommand('mceInsertContent', false, '[showcase id="' + v + '"]');
                            }
                            return false;
                        }
                    });

                    var galleries = wp_showcase.galleries.replace(new RegExp("&quot;", "g"), String.fromCharCode(34));
                    galleries = jQuery.parseJSON(galleries);
                    for(var i in galleries){
                        mlb.add(galleries[i].name, galleries[i].id);
                    }

                    // Return the new listbox instance
                    return mlb;
            }

            return null;
        },
        getInfo : function() {
            return {
                longname : 'Showcase Gallery Shortcode',
                author : 'Gilbert Pellegrom',
                authorurl : 'http://gilbert.pellegrom.me',
                infourl : 'http://showcase.dev7studios.com',
                version : '1.0'
            };
        }
    });
    tinymce.PluginManager.add('showcase', tinymce.plugins.Showcase);
})();