<?php
    /**
     * set CSS namespace in HTML, JS and CSS file
     */
    class namespaceCSS
    {

        public $html;
        public $scss;
        public $js;
        public $selectors;

        function __construct(stdClass $selectors = null, array $html = [], array $scss = [], array $js = [], string $directory = '')
        {
            $html_length = count($html);
            $html_content = '';
            while ($html_length--)
            {
                if ( is_file( $directory . $html[$html_length] ) )
                {
                    $html_content .= file_get_contents( $directory . $html[$html_length] );
                }
                else
                {
                    $html_content .= $html[$html_length];
                }
            }
            $scss_length = count($scss);
            $scss_content = '';
            while ($scss_length--)
            {
                if ( is_file( $directory . '/scss' . $scss[$scss_length] ) )
                {
                    $scss_content .= file_get_contents( $directory . '/scss' . $scss[$scss_length] );
                }
                else
                {
                    $scss_content .= $scss[$scss_length];
                }
            }
            $js_length = count($js);
            $js_content = '';
            while ($js_length--)
            {
                if ( is_file( $directory . '/js' . $js[$js_length] ) )
                {
                    $js_content .= file_get_contents( $directory . '/js' . $js[$js_length] );
                }
                else
                {
                    $js_content .= $js[$js_length];
                }
            }

            if ($selectors === null)
            {
                $selectors = $this->find_selectors($html_content, $scss_content, $js_content);
            }

            if ($selectors !== null)
            {
                $return = $this->set_namespace($selectors, $html_content,  $scss_content,  $js_content);
            }

            $this->html = $return->html;
            $this->scss = $return->scss;
            $this->js = $return->js;
            $this->selectors = $selectors;
        }

        private function set_namespace(stdClass $selectors, string $html = '', string $scss = '', string $js = '')
        {
            $html = $html;
            $scss = $scss;
            $js = $js;

            $html_ids = $selectors->html->id;
            $html_classes = $selectors->html->class;

            $scss_ids = $selectors->scss->id;
            $scss_classes = $selectors->scss->class;

            $js_ids = [
                [
                    $selectors->js->id->get_id,
                    '/((?:getElementById\b)\(.*?)(?<!\-|\w)(',
                    '\b)(?!\-)/'
                ],
                [
                    $selectors->js->id->id_jQuery,
                    '/((?:\$)\(.*?(?=\#)\#)(',
                    '\b)(?!\-)/'
                ]
            ];

            $js_classes = [
                [
                    $selectors->js->class->get_class,
                    '/((?:getElementsByClassName\b)\(.*?)(?<!\-|\w)(',
                    '\b)(?!\-)/'
                ],
                [
                    $selectors->js->class->class_jQuery,
                    '/((?:\$)\(.*?(?=\.)\.)(',
                    '\b)(?!\-)/'
                ],
                [
                    $selectors->js->class->arh_jQuery,
                    '/((?:\.addClass\b|\.hasClass\b|\.removeClass\b)\(.*?)(?<!\-|\w)(',
                    '\b)(?!\-)/'
                ]
            ];

            if ( count($html_ids) > 0 )
            {
                foreach ($html_ids as $value)
                {
                    $html = $this->match_set_namespace(
                        $html,
                        '/(id(?(?=\s+)\s+)\=(?(?=\s+)\s+)\"[^"]*?)((?<!\-|\w)' . $value . '\b(?!\-|\w))/'
                    );
                    /* WORKAROUND: match xlink:href in <svg> */
                    $html = preg_replace_callback(
                                    '/' . REGEX_SVG_ALL .'/',
                                    function ($match) use($value) {
                                        if (preg_match('/(?:xlink:href|href|fill|filter|mask|clip-path).*?(?=\#)(?<!\-)\#' . $value . '\b(?!\-)/', $match[0]))
                                        {
                                            return $match[0] = $this->match_set_namespace(
                                                $match[0],
                                                '/((?:xlink:href|href|fill|filter|mask|clip-path).*?(?=\#)(?<!\-)\#)(' . $value . '\b)(?!\-)/'
                                            );
                                        }
                                        else
                                        {
                                            return $match[0];
                                        }
                                    },
                                    $html
                                );
                }
            }
            if ( count($html_classes) > 0 )
            {
                foreach ($html_classes as $value)
                {
                    $html = $this->match_set_namespace(
                        $html,
                        '/(class\b(?(?=\s+)\s+)\=(?(?=\s+)\s+).*?(?|(?:(?=\s+)\s+)|(?:(?=\")\")|(?:(?=\')\')))(' . $value . '\b(?!\-|\w))/'
                    );
                }
            }
            if ( count($scss_ids) > 0 )
            {
                foreach ($scss_ids as $value)
                {
                    $scss = $this->match_set_namespace(
                        $scss,
                        '/(\#)(' . $value . '\b)(?!\-|\w)/'
                    );
                }
            }
            if ( count($scss_classes) > 0 )
            {
                foreach ($scss_classes as $value)
                {
                    $scss = $this->match_set_namespace(
                        $scss,
                        '/(\.)(' . $value . '\b)(?!\-|\w)/'
                    );
                }
            }
            foreach ($js_ids as $id) {
                if (count($id[0]) > 0) {
                    foreach ($id[0] as $value)
                    {
                        $js = $this->match_set_namespace(
                            $js,
                            $id[1] . $value . $id[2]
                        );
                    }
                }
            }
            foreach ($js_classes as $class) {
                if (count($class[0]) > 0) {
                    foreach ($class[0] as $value)
                    {
                        $js = $this->match_set_namespace(
                            $js,
                            $class[1] . $value . $class[2]
                        );
                    }
                }
            }

            return (object) [
                'html' => $html,
                'scss' => $scss,
                'js' => $js
            ];
        }

        /*
         * match and set namespace
         */
        private function match_set_namespace(string $content, $pattern)
        {
            $content = preg_replace_callback(
                            $pattern,
                            function($match) {
                                if ( preg_match('/' . constant('NAMESPACE') . $match[2] . '\w+\b/', $match[0]) )
                                {
                                    return $match[1] . $match[2];
                                }
                                else
                                {
                                    return $match[1] . constant('NAMESPACE') . $match[2];
                                }
                            },
                            $content
                        );
            return $content;
        }

        /*
         * find all selectors
         */
        private function find_selectors(string $html = '', string $scss = '', string $js = '')
        {
            $pattern_id_html = '/' . REGEX_ID_HTML . '/';
            $pattern_class_html = '/' . REGEX_CLASS_HTML . '/';
            $pattern_tag_html = '/' . REGEX_TAG_HTML . '/';

            $pattern_all_scss = '/' . REGEX_ALL_SCSS . '/';
            $pattern_id_scss = '/' . REGEX_ID_SCSS . '/';
            $pattern_class_scss = '/' . REGEX_CLASS_SCSS . '/';
            $pattern_tag_scss = '/' . REGEX_TAG_SCSS . '/';

            $pattern_get_id_js = '/' . REGEX_GET_ID_JS . '/';
            $pattern_get_class_js = '/' . REGEX_GET_CLASS_JS . '/';

            $pattern_add_rem_hasClass_jQuery = '/' . REGEX_ADD_REM_HASCLASS_JQUERY . '/';
            $pattern_selectors_jQuery = '/' . REGEX_SELECTORS_JQUERY . '/';
            $pattern_id_selectors_jQuery = '/' . REGEX_ID_SELECTORS_JQUERY . '/';
            $pattern_class_selectors_jQuery = '/' . REGEX_CLASS_SELECTORS_JQUERY . '/';


            /* filter html match result */
            preg_match_all( $pattern_id_html, $html, $html_ids );

            preg_match_all( $pattern_class_html, $html, $html_class_match );
            /* WORKAROUND: for multiple whitespaces in html e.g. class="class    class class" */
            preg_match_all('/(?|(.+?)(?:\,)|(.+))/', preg_replace( '/' . REGEX_SPACES . '/', ',', join(',',array_filter($html_class_match[1], function($value) { return $value !== ''; } ))), $html_classes);

            preg_match_all( $pattern_tag_html, $html, $html_tags );

            $html_ids = $this->filter_unique_flatten_array($html_ids[1]);
            $html_classes = array_unique( $html_classes[1] );
            $html_tags = $this->filter_unique_flatten_array($html_tags[1]);


            /* filter scss match result */
            preg_match_all( $pattern_all_scss, $scss, $scss_matches );
            $scss_result = str_replace(',,', ',', preg_replace( '/' . REGEX_SPACES . '/', ',', join( ',', $scss_matches[1] )));

            preg_match_all( $pattern_id_scss, $scss_result, $scss_ids );
            foreach( $scss_ids as $key => $id )
            {
                $scss_ids[$key] = str_replace( '#', '', $id );
            }
            $scss_ids = $this->filter_unique_flatten_array($scss_ids);

            preg_match_all( $pattern_class_scss, $scss_result, $scss_classes );
            foreach( $scss_classes as $key => $class )
            {
                $scss_classes[$key] = str_replace( '.', '', $class );
            }
            $scss_classes = $this->filter_unique_flatten_array($scss_classes);

            preg_match_all( $pattern_tag_scss, $scss_result, $scss_tags );
            $scss_tags = $this->filter_unique_flatten_array($scss_tags);


            /* filter js match result */
            preg_match_all( $pattern_get_id_js, $js, $js_get_ids );
            $js_get_ids = $this->filter_unique_flatten_array($js_get_ids[1]);

            preg_match_all( $pattern_get_class_js, $js, $js_get_classes );
            $js_get_classes = $this->filter_unique_flatten_array($js_get_classes[1]);

            preg_match_all( $pattern_add_rem_hasClass_jQuery, $js, $js_add_rem_hasClass_match_jQuery );
            preg_match_all( '/[\w\-]+/', join(',', $js_add_rem_hasClass_match_jQuery[1]), $js_add_rem_hasClass_jQuery);
            $js_add_rem_hasClass_jQuery = $this->filter_unique_flatten_array($js_add_rem_hasClass_jQuery[0]);

            preg_match_all( $pattern_selectors_jQuery, $js, $js_selectors_jQuery );

            preg_match_all( $pattern_id_selectors_jQuery, join(',', $js_selectors_jQuery[1]), $js_id_selectors_jQuery );
            foreach( $js_id_selectors_jQuery as $key => $id )
            {
                $js_id_selectors_jQuery[$key] = str_replace( '#', '', $id );
            }
            $js_id_selectors_jQuery = $this->filter_unique_flatten_array($js_id_selectors_jQuery[0]);

            preg_match_all( $pattern_class_selectors_jQuery, join(',', $js_selectors_jQuery[1]), $js_class_selectors_jQuery );
            foreach( $js_class_selectors_jQuery as $key => $class )
            {
                $js_class_selectors_jQuery[$key] = str_replace( '#', '', $class );
            }
            $js_class_selectors_jQuery = $this->filter_unique_flatten_array($js_class_selectors_jQuery[0]);

            return (object) [
                'html' => (object) [
                    'id' => $html_ids,
                    'class' => $html_classes,
                    'tag' => $html_tags
                ],
                'scss' => (object) [
                    'id' => $scss_ids,
                    'class' => $scss_classes,
                    'tag' => $scss_tags
                ],
                'js' => (object) [
                    'id' => (object) [
                        'get_id' => $js_get_ids,
                        'id_jQuery' => $js_id_selectors_jQuery
                    ],
                    'class' => (object) [
                        'get_class' => $js_get_classes,
                        'class_jQuery' => $js_class_selectors_jQuery,
                        'arh_jQuery' => $js_add_rem_hasClass_jQuery
                    ]
                ]
            ];

        }

        /*
         * flatten array
         */
        private function flatten_array(array $array)
        {
            $return = [];
            array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
            return $return;
        }

        /*
         * filter, unique and flatten array
         */
        private function filter_unique_flatten_array(array $array)
        {
            return array_filter( array_unique( $this->flatten_array( $array ) ), function($value) { return $value !== ''; } );
        }

    }

?>
