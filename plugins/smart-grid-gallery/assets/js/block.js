(function (blocks, i18n, element, components) {
    var SelectControl = components.SelectControl;
    var el = element.createElement;

    blocks.registerBlockType('origincode-video-gallery/gallery', {
        title: 'Video Gallery',
        icon: 'format-gallery',
        category: 'origincode-video-gallery',
        attributes: {
            gallery_id: {type: 'string'}
        },
        edit: function (props) {
            var focus = props.focus;
            props.attributes.gallery_id =  props.attributes.gallery_id &&  props.attributes.gallery_id != '0' ?  props.attributes.gallery_id : false;
            return [
                !focus && el(
                    SelectControl,
                    {
                        label: 'Select Gallery',
                        value: props.attributes.gallery_id ? parseInt(props.attributes.gallery_id) : 0,
                        instanceId: 'origincode-vdg-gallery-selector',
                        onChange: function (value) {
                            props.setAttributes({gallery_id: value});
                        },
                        options: origincodeVdgBlockI10n.galleries,
                    }
                ),
                el('div',{}, props.attributes.gallery_id ? 'Gallery: ' + origincodeVdgBlockI10n.galleryMetas[props.attributes.gallery_id] : 'Select Gallery')
            ];
        },
        save: function (props) {
            if(typeof props.attributes.gallery_id != 'undefined' && props.attributes.gallery_id != 0){
                return el('p', {}, '[origincode_videogallery id="'+props.attributes.gallery_id+'"]');
            } else {
                return el('p', {}, 'Gallery not selected');
            }

        },
    });
})(
    window.wp.blocks,
    window.wp.i18n,
    window.wp.element,
    window.wp.components
);