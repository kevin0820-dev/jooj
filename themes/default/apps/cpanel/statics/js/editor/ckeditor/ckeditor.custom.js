// Default ckeditor
CKEDITOR.replace( 'editor1', {
    on: {
        contentDom: function( evt ) {
            evt.editor.editable().on( 'contextmenu', function( contextEvent ) {
                var path = evt.editor.elementPath();

                if ( !path.contains( 'table' ) ) {
                    contextEvent.cancel();
                }
            }, null, null, 5 );
        },
        change: function(evt) {
            var data = evt.editor.getData();
            document.getElementById('editor1').innerHTML = data;
        }
    },
});
document.querySelector("form").addEventListener("submit", function() {
    CKEDITOR.updateSourceElement();
});
// Inline ckeditor
CKEDITOR.disableAutoInline = true;
//init the area to be done
CKEDITOR.inline( 'area1', {
    toolbar: [
        {
            name: 'basicstyles',
            groups: ['basicstyles'],
            items: [
                'Format',
                'Bold',
                'Italic',
                'Underline'
            ]
        },
        {
            name: 'paragraph',
            groups: [
                'list',
                'indent',
                'blocks',
                'align',
                'bidi'
            ],
            items: [
                'NumberedList',
                'BulletedList',
                'JustifyLeft',
                'JustifyCenter',
                'JustifyRight',
            ]
        },
        {
            name: 'links',
            items: [
                'Link',
                'Unlink'
            ]
        },
    ],
    fillEmptyBlocks: false,
    autoParagraph: false
} );