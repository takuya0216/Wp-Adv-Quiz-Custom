(function (blocks, i18n, element, data, wp) {
    var el = element.createElement;
    var __ = i18n.__;
    var withSelect = data.withSelect;
    var selectControl = wp.components.SelectControl;
    var serverSideRender = wp.serverSideRender;

    function createSelectElment(el, props, quizner, withServerRender) {
        return el(
            selectControl, {
                className: 'wpAdvQuiz_block_select',
                label: '',
                value: props.attributes.metaFieldValue,
                onChange: function (content) {
                    var c = isNaN(content) ? '' : content;

                    wp.data.dispatch('core/block-editor').updateBlockAttributes(props.clientId, {
                        shortcode: '[WpAdvQuiz ' + c + ']',
                        metaFieldValue: parseInt(c)
                    });
                },
                options: quizner
            },
            withServerRender ? el(serverSideRender, {
                key: 'editable',
                block: 'wp-adv-quiz/quiz',
                attributes: props.attributes
            }) : null
        )
    }

    wp.blocks.registerBlockType('wp-adv-quiz/quiz', {
        title: 'Wp-Adv-Quiz',
        icon: 'universal-access-alt',
        category: 'common',
        example: {},
        edit: withSelect(function (select) {
            var attr = select('core/blocks').getBlockType('wp-adv-quiz/quiz').attributes;

            if (attr && attr.idner) {
                return {
                    quizzes: attr.idner
                }
            } else {
                return {
                    quizzes: __("Something goes wrong please reload page")
                }
            }
        })(function (props) {
            if (!props.quizzes) {
                return 'Loading...';
            }

            if (props.quizzes.length === 0) {
                return 'No posts';
            }

            var quizner = [];
            quizner.push({
                label: __("-Select Quiz-"),
                value: ''
            });
            props.quizzes.forEach(function (v) {
                quizner.push({
                    label: v.title,
                    value: v.id,
                })
            });

            return el(
                wp.element.Fragment,
                {},
                el(
                    wp.blockEditor.BlockControls,
                    props
                ),
                el(
                    wp.blockEditor.InspectorControls,
                    {},
                    el(
                        wp.components.PanelBody,
                        {},
                        el(
                            'div',
                            {
                                className: 'wpAdvQuiz_block_container',
                                key: 'inspector',
                            },
                            createSelectElment(el, props, quizner, false)
                        )
                    )
                ),
                props.attributes.metaFieldValue > 0 ? null : createSelectElment(el, props, quizner, true),
                el(serverSideRender, {
                    key: 'editable',
                    block: 'wp-adv-quiz/quiz',
                    attributes: props.attributes
                })
            );
        }),
        save: function (props) {
            var n = props.attributes.metaFieldValue;

            return n ? wp.element.createElement('div', {}, '[WpAdvQuiz '+ n +']') : null;
        },
    });
})(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.data, window.wp);
