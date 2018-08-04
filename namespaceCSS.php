<?php

    class namespaceCSS {

        public static function flatten_array(array $array)
        {
            $return = array();
            array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
            return $return;
        }

        public static function find_all_IDs_CLASSes_TAGs($HTML_content = null , $SCSS_content = null, $JS_content = null)
        {
            $pattern_all_ids_classes_tags_SCSS = '/(?|(?:\{(?(?=\s+)\s+)[^\{\$]*?(?=\}))|(?=\{)\{[^\{\}]*?(?=\{(?(?=\s+)\s+)\$)\{[^\{]*?(?=\})\}(?(?=[^\{]*?(?=\#\{)\#\{[^\{]*?(?=\})\})(?:[^\{]*?(?=\#\{)\#\{[^\{]*?(?=\})\})+)[^\{]*?(?=\})|(?(?=\s+)\s+)(?:\@(?:-webkit-keyframes)).*?(?=\}(?(?=\s+)\s+)\})|(?(?=\s+)\s+)(?:\@(?:keyframes)).*?(?=\}(?(?=\s+)\s+)\}))(*SKIP)(*FAIL)|(?|(?(?=(?:\{(?(?=\s+)\s+)\{)|(?:\{(?(?=\s+)\s+)\})|(?:\}(?(?=\s+)\s+)\})|(?:\}(?(?=\s+)\s+)\{))\0|(?:\{|\})(?(?=\s+)\s+)(?(?=\@)\0|([^\%\;\@]*?(?(?=\#\{\$)\#\{\$.*?(?=\})))(?(?=\s+)\s+))(?(?=\}|\{\$)\0)(?=\{))|(?=\;)\;(?(?=\s+)\s+)([^\%\;\@\{\}]*?(?(?=\#\{\$)\#\{\$.*?(?=\})\}))(?(?=\s+)\s+)(?=\{)|(^[^\%\;\@]*?(?(?=\#\{\$)\#\{\$.*?(?=\})))(?=\{))/';

            $pattern_all_ids_SCSS = '/(?(?=\#\w+)\#([\w\-]+)(?:(?=\,)|(?=\:)|(?=\s+)|(?=\.)|(?=\+)|(?=\~)|(?=\>)|(?=\[)|\#[\w\-]+|)|\0)/';
            $pattern_all_classes_SCSS = '/(?(?=\.\w+)\.([\w\-]+)(?:(?=\,)|(?=\:)|(?=\s+)|(?=\#)|(?=\+)|(?=\~)|(?=\>)|(?=\[)|\.[\w\-]+|)|\0)/';
            $pattern_all_tags_SCSS = '/(?(?=\#\w+)\#\w+|\0)(*SKIP)(*FAIL)|(?(?=\.\w+)\.\w+|\0)(*SKIP)(*FAIL)|(?(?=\-\w+)\-\w+|\0)(*SKIP)(*FAIL)|(?(?=\_\w+)\_\w+|\0)(*SKIP)(*FAIL)|(?(?=(?:\:lang\b|\:nth-child\b|\:nth-last-child\b|\:nth-last-of-type\b|\:nth-of-type\b)\((?(?=\s+)\s+).+?(?=\))\))(?:\:lang\b|\:nth-child\b|\:nth-last-child\b|\:nth-last-of-type\b|\:nth-of-type\b)\((?(?=\s+)\s+).+?(?=\))\)|\0)(*SKIP)(*FAIL)|(?(?=\:\w+)\:\w+|\0)(*SKIP)(*FAIL)|(?(?=\[(?(?=\s+)\s+)\w+)\[(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(?(?=\=(?(?=\s+)\s+)\w+)\=(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(?(?=\"(?(?=\s+)\s+)\w+)\"(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(?(?=\'(?(?=\s+)\s+)\w+)\'(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(\w+)/';

            $pattern_all_ids_HTML = '/id\b(?(?=\s+)\s+)\=(?(?=\s+)\s+)\"(?(?=\s+)\s+)([\w+\-]+)(?(?=\s+)\s+)(?=\")/';
            $pattern_all_classes_HTML = '/class\b(?(?=\s+)\s+)\=(?(?=\s+)\s+)\"(?(?=\s+)\s+)(.*?)(?(?=\s+)\s+)(?=\")/';
            $pattern_all_tags_HTML = '/<(?(?=\s+)\s+)(\w+)/';

            $pattern_get_id_JS = '/(?:getElementById\b)\(.*?[\"\'](?(?=\s+)\s+)([\w+\-]+)(?(?=\s+)\s+)[\"\']/';
            $pattern_get_class_JS = '/(?:getElementsByClassName\b)\(.*?[\"\'](?(?=\s+)\s+)([\w+\-]+)(?(?=\s+)\s+)[\"\']/';

            $pattern_add_rem_hasClass_JQuery = '/(?:\.addClass\b|\.hasClass\b|\.removeClass\b)\((?(?=\s+)\s+)[\"\'](?(?=\s+)\s+)(.+?)(?=\"|\')[\"\']\)/';
            $pattern_selector_all_JQuery = '/(?:\$)\((?(?=\s+)\s+)[\"\'](?(?=\s+)\s+)(?(?=[a-zA-Z0-9\*\s\-\:\>\[\]\~\+\*\,\|\=\$\^\'\"\_\d]+?)[a-zA-Z0-9\*\s\-\:\>\[\]\~\+\*\,\|\=\$\^\'\"\_\d]+?)(?=\#|\.)(?|([\#\.a-zA-Z0-9\*\s\-\:\>\[\]\~\+\*\,\|\=\$\^\"\'\_\d\(\)]+?))(?(?=\s+)\s+)[\"\'](?(?=\s+)\s+)\)/';

            $pattern_filter_id_JQuery = '/\#[\w\-]+/';
            $pattern_filter_class_JQuery = '/\.[\w\-]+/';

            if ($SCSS_content !== null)
            {
                preg_match_all( $pattern_all_ids_classes_tags_SCSS, $SCSS_content, $SCSS_matches );
                $SCSS_match_result = str_replace(',,', ',', preg_replace( '/\s+/', ',', join( ',', $SCSS_matches[1] )));

                preg_match_all( $pattern_all_ids_SCSS, $SCSS_match_result, $SCSS_ids );
                preg_match_all( $pattern_all_classes_SCSS, $SCSS_match_result, $SCSS_classes );
                preg_match_all( $pattern_all_tags_SCSS, $SCSS_match_result, $SCSS_tags );

                foreach( $SCSS_ids as $id_key => $id_value )
                {
                    $SCSS_ids[$id_key] = str_replace( '#', '', $id_value );
                }
                $SCSS_ids = array_filter( array_unique( namespaceCSS::flatten_array( $SCSS_ids ) ), function($value) { return $value !== ''; } );

                foreach( $SCSS_classes as $class_key => $class_value )
                {
                    $SCSS_classes[$class_key] = str_replace( '.', '', $class_value );
                }
                $SCSS_classes = array_filter( array_unique( namespaceCSS::flatten_array( $SCSS_classes ) ), function($value) { return $value !== ''; } );

                $SCSS_tags = array_filter( array_unique( namespaceCSS::flatten_array( $SCSS_tags ) ), function($value) { return $value !== ''; } );
            }

            if ($HTML_content !== null)
            {
                preg_match_all( $pattern_all_ids_HTML, $HTML_content, $HTML_ids );
                preg_match_all( $pattern_all_classes_HTML, $HTML_content, $HTML_class_match );
                /*workaround for whitespaces in HTML e.g. class="class    class class"*/
                preg_match_all('/(?|(.+?)(?:\,)|(.+))/', preg_replace( '/\s+/', ',', join(',',array_filter($HTML_class_match[1], function($value) { return $value !== ''; } ))), $HTML_classes);
                preg_match_all( $pattern_all_tags_HTML, $HTML_content, $HTML_tags );

                $HTML_ids = array_filter( array_unique( namespaceCSS::flatten_array( $HTML_ids[1] ) ), function($value) { return $value !== ''; } );

                $HTML_classes = array_unique( $HTML_classes[1] );

                $HTML_tags = array_filter( array_unique( namespaceCSS::flatten_array( $HTML_tags[1] ) ), function($value) { return $value !== ''; } );
            }

            if ($JS_content !== null)
            {
                preg_match_all( $pattern_get_id_JS, $JS_content, $JS_get_ids );
                $JS_get_ids = array_filter( array_unique( namespaceCSS::flatten_array( $JS_get_ids[1] ) ), function($value) { return $value !== ''; } );

                preg_match_all( $pattern_get_class_JS, $JS_content, $JS_get_classes );
                $JS_get_classes = array_filter( array_unique( namespaceCSS::flatten_array( $JS_get_classes[1] ) ), function($value) { return $value !== ''; } );

                preg_match_all( $pattern_add_rem_hasClass_JQuery, $JS_content, $JS_add_rem_hasClass_match_JQuery );
                preg_match_all( '/[\w\-]+/', join(',', $JS_add_rem_hasClass_match_JQuery[1]), $JS_add_rem_hasClass_JQuery);

                $JS_add_rem_hasClass_JQuery = array_filter( array_unique( namespaceCSS::flatten_array( $JS_add_rem_hasClass_JQuery[0] ) ), function($value) { return $value !== ''; } );

                preg_match_all( $pattern_selector_all_JQuery, $JS_content, $JS_selector_all_JQuery );

                preg_match_all( $pattern_filter_id_JQuery, join(',', $JS_selector_all_JQuery[1]), $JS_selector_id_jQuery );
                foreach( $JS_selector_id_jQuery as $id_key => $id_value )
                {
                    $JS_selector_id_jQuery[$id_key] = str_replace( '#', '', $id_value );
                }
                $JS_selector_id_jQuery = array_filter( array_unique( namespaceCSS::flatten_array( $JS_selector_id_jQuery[0] ) ), function($value) { return $value !== ''; } );

                preg_match_all( $pattern_filter_class_JQuery, join(',', $JS_selector_all_JQuery[1]), $JS_selector_class_jQuery );
                foreach( $JS_selector_class_jQuery as $class_key => $class_value )
                {
                    $JS_selector_class_jQuery[$class_key] = str_replace( '.', '', $class_value );
                }
                $JS_selector_class_jQuery = array_filter( array_unique( namespaceCSS::flatten_array( $JS_selector_class_jQuery[0] ) ), function($value) { return $value !== ''; } );
            }

            if ($HTML_content !== null && $SCSS_content !== null && $JS_content !== null)
            {
                return [
                    [
                        $SCSS_ids,
                        $SCSS_classes,
                        $SCSS_tags
                    ],
                    [
                        $HTML_ids,
                        $HTML_classes,
                        $HTML_tags
                    ],
                    [
                        $JS_ids = [
                            $JS_get_ids,
                            $JS_selector_id_jQuery
                        ],
                        $JS_classes = [
                            $JS_get_classes,
                            $JS_add_rem_hasClass_JQuery,
                            $JS_selector_class_jQuery
                        ]
                    ]
                ];
            }
        }

        public static function set_namespace($all_IDs_CLASSes_TAGs, $HTML_content = null , $SCSS_content = null, $JS_content = null, $namespace = null)
		{

			$SCSS_ids = $all_IDs_CLASSes_TAGs[0][0];
			$SCSS_classes = $all_IDs_CLASSes_TAGs[0][1];

			$HTML_ids = $all_IDs_CLASSes_TAGs[1][0];
			$HTML_classes = $all_IDs_CLASSes_TAGs[1][1];

			$JS_get_ids = $all_IDs_CLASSes_TAGs[2][0][0];
			$JS_selector_id_jQuery = $all_IDs_CLASSes_TAGs[2][0][1];

			$JS_get_classes = $all_IDs_CLASSes_TAGs[2][1][0];
			$JS_add_rem_hasClass_JQuery = $all_IDs_CLASSes_TAGs[2][1][1];
			$JS_selector_class_jQuery = $all_IDs_CLASSes_TAGs[2][1][2];

            if ( $HTML_content !== null && $namespace !== null )
            {
                if ( count($HTML_ids) > 0 )
    			{
    				foreach ($HTML_ids as $HTML_id_search_value)
    				{
    					$HTML_content = preg_replace_callback(
    									'/(id(?(?=\s+)\s+)\=(?(?=\s+)\s+)\".*?)(' . $HTML_id_search_value . '\b.*?(?=\"))/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$HTML_content
    								);
    					$HTML_content = preg_replace_callback(
    									'/<svg[^>]*?[^>]*?>([^<]*(?(?!<\/svg>)<))*<\/svg>/',
    									function ($match) use($HTML_id_search_value, $namespace) {
    										if (preg_match('/(?:xlink:href|href).*?\#' . $HTML_id_search_value . '\b/', $match[0]))
    										{
    											return $match[0] = preg_replace_callback(
    															'/((?:xlink:href|href).*?\#)(' . $HTML_id_search_value . '\b)/',
    															function ($matches) use ($namespace) {
    																if ( preg_match('/' . $namespace . '\b/', $matches[0]) )
    																{
    																	return $matches[1] . $matches[2];
    																}
    																else
    																{
    																	return $matches[1] . $namespace . $matches[2];
    																}
    															},
    															$match[0]
    														);
    										}
    										else
    										{
    											return $match[0];
    										}
    									},
    									$HTML_content
    								);
    				}
    			}
    			if ( count($HTML_classes) > 0 )
    			{
    				foreach ($HTML_classes as $HTML_class_search_value)
    				{
    					$HTML_content = preg_replace_callback(
    									'/(class\b(?(?=\s+)\s+)\=(?(?=\s+)\s+).*?(?|(?:(?=\s+)\s+)|(?:(?=\")\")|(?:(?=\')\')))(' . $HTML_class_search_value . '\b.*?(?=\"))/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$HTML_content
    								);
    				}
    			}
            }
            if ( $SCSS_content !== null && $namespace !== null )
            {
                if ( count($SCSS_ids) > 0 )
    			{
    				foreach ($SCSS_ids as $SCSS_id_search_value)
    				{
    					$SCSS_content = preg_replace_callback(
    									'/(\#)(' . $SCSS_id_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$SCSS_content
    								);
    				}
    			}
    			if ( count($SCSS_classes) > 0 )
    			{
    				foreach ($SCSS_classes as $SCSS_class_search_value)
    				{
    					$SCSS_content = preg_replace_callback(
    									'/(\.)(' . $SCSS_class_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$SCSS_content
    								);
    				}
    			}
            }
            if ( $JS_content !== null && $namespace !== null )
            {
                if ( count($JS_get_ids) > 0 )
    			{
    				foreach ($JS_get_ids as $JS_get_id_search_value)
    				{
    					$JS_content = preg_replace_callback(
    									'/((?:getElementById\b)\(.*?)(' . $JS_get_id_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$JS_content
    								);
    				}
    			}
    			if ( count($JS_selector_id_jQuery) > 0 )
    			{
    				foreach ($JS_selector_id_jQuery as $JS_selector_id_jQuery_search_value)
    				{
    					$JS_content = preg_replace_callback(
    									'/((?:\$)\(.*?(?=\#)\#)(' . $JS_selector_id_jQuery_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$JS_content
    								);
    				}
    			}
    			if ( count($JS_get_classes) > 0 )
    			{
    				foreach ($JS_get_classes as $JS_get_class_search_value)
    				{
    					$JS_content = preg_replace_callback(
    									'/((?:getElementsByClassName\b)\(.*?)(' . $JS_get_class_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$JS_content
    								);
    				}
    			}
    			if ( count($JS_add_rem_hasClass_JQuery) > 0 )
    			{
    				foreach ($JS_add_rem_hasClass_JQuery as $JS_add_rem_hasClass_JQuery_search_value)
    				{
    					$JS_content = preg_replace_callback(
    									'/((?:\.addClass\b|\.hasClass\b|\.removeClass\b)\(.*?)(' . $JS_add_rem_hasClass_JQuery_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$JS_content
    								);
    				}
    			}
    			if ( count($JS_selector_class_jQuery) > 0 )
    			{
    				foreach ($JS_selector_class_jQuery as $JS_selector_class_jQuery_search_value)
    				{
    					$JS_content = preg_replace_callback(
    									'/((?:\$)\(.*?(?=\.)\.)(' . $JS_selector_class_jQuery_search_value . '\b)/',
    									function ($match) use ($namespace) {
    										if ( preg_match('/' . $namespace . '\b/', $match[0]) )
    										{
    											return $match[1] . $match[2];
    										}
    										else
    										{
    											return $match[1] . $namespace . $match[2];
    										}
    									},
    									$JS_content
    								);
    				}
    			}
            }

			return [
				$HTML_content,
				$SCSS_content,
				$JS_content
			];
		}

    }

?>
