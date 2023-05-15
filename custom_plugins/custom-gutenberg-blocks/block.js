( function() {

    var __ = wp.i18n.__;
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;

    registerBlockType(
        'block/placement-block', {
            title: __( 'Víťažný piedestál', '' ),
            icon: 'awards',
            category: 'common',
            attributes: {
                firstPlaceName: {
                    type: 'string',
                    default: '',
                },
                firstPlaceDesc: {
                    type: 'string',
                    default: '',
                },
                secondPlaceName: {
                    type: 'string',
                    default: '',
                },
                secondPlaceDesc: {
                    type: 'string',
                    default: '',
                },
                thirdPlaceName: {
                    type: 'string',
                    default: '',
                },
                thirdPlaceDesc: {
                    type: 'string',
                    default: '',
                },
            },
            edit: function (props) {
                console.log(props)
                var attributes = props.attributes;
                var setAttributes = props.setAttributes;
                var onChangeFirstPlaceName = function (value) {
                    setAttributes({firstPlaceName: value});
                };
                var onChangeFirstPlaceDesc = function (value) {
                    setAttributes({firstPlaceDesc: value});
                };
                var onChangeSecondPlaceName = function (value) {
                    setAttributes({secondPlaceName: value});
                };
                var onChangeSecondPlaceDesc = function (value) {
                    setAttributes({secondPlaceDesc: value});
                };
                var onChangeThirdPlaceName = function (value) {
                    setAttributes({thirdPlaceName: value});
                };
                var onChangeThirdPlaceDesc = function (value) {
                    setAttributes({thirdPlaceDesc: value});
                };
                return el(
                    'div',
                    {className: props.className},
                    el(
                        'h2',
                        null,
                        __('Prvé miesto')
                    ),
                    el(
                        'label',
                        null,
                        __('Meno'),
                        el(
                            'input',
                            {
                                type: 'text',
                                value: attributes.firstPlaceName,
                                onChange: function (event) {
                                    onChangeFirstPlaceName(event.target.value);
                                }
                            }
                        )
                    ),
                    el(
                        'label',
                        null,
                        __('Popis'),
                        el(
                            'input',
                            {
                                type: 'text',
                                value: attributes.firstPlaceDesc,
                                onChange: function (event) {
                                    onChangeFirstPlaceDesc(event.target.value);
                                }
                            }
                        )
                    ),
                    el(
                        'h2',
                        null,
                        __('Druhé miesto')
                    ),
                    el(
                        'label',
                        null,
                        __('Meno'),
                        el(
                            'input',
                            {
                                type: 'text',
                                value: attributes.secondPlaceName,
                                onChange: function (event) {
                                    onChangeSecondPlaceName(event.target.value);
                                }
                            }
                        )
                    ),
                    el(
                        'label',
                        null,
                        __('Popis'),
                        el(
                            'input',
                            {
                                type: 'text',
                                value: attributes.secondPlaceDesc,
                                onChange: function (event) {
                                    onChangeSecondPlaceDesc(event.target.value);
                                }
                            }
                        )
                    ),
                    el(
                        'h2',
                        null,
                        __('Tretie miesto')
                    ),
                    el(
                        'label',
                        null,
                        __('Meno'),
                        el(
                            'input',
                            {
                                type: 'text',
                                value: attributes.thirdPlaceName,
                                onChange: function (event) {
                                    onChangeThirdPlaceName(event.target.value);
                                }
                            }
                        )
                    ),
                    el(
                        'label',
                        null,
                        __('Popis'),
                        el(
                            'input',
                            {
                                type: 'text',
                                value: attributes.thirdPlaceDesc,
                                onChange: function (event) {
                                    onChangeThirdPlaceDesc(event.target.value);
                                }
                            }
                        )
                    ),
                )
            },
            save: function( props ) {
                return null
            },
        }
    );

})();