<?php
// namespace
define('NAMESPACE', 'HTML5-BC__');
// match spaces
define('REGEX_SPACES', '\s+');


// define patterns to match CSS selectors
// SCSS
define('REGEX_ALL_SCSS', '(?|(?:\{(?(?=\s+)\s+)[^\{\$]*?(?=\}))|(?=\{)\{[^\{\}]*?(?=\{(?(?=\s+)\s+)\$)\{[^\{]*?(?=\})\}(?(?=[^\{]*?(?=\#\{)\#\{[^\{]*?(?=\})\})(?:[^\{]*?(?=\#\{)\#\{[^\{]*?(?=\})\})+)[^\{]*?(?=\})|(?(?=\s+)\s+)(?:\@(?:-webkit-keyframes)).*?(?=\}(?(?=\s+)\s+)\})|(?(?=\s+)\s+)(?:\@(?:keyframes)).*?(?=\}(?(?=\s+)\s+)\}))(*SKIP)(*FAIL)|(?|(?(?=(?:\{(?(?=\s+)\s+)\{)|(?:\{(?(?=\s+)\s+)\})|(?:\}(?(?=\s+)\s+)\})|(?:\}(?(?=\s+)\s+)\{))\0|(?:\{|\})(?(?=\s+)\s+)(?(?=\@)\0|([^\%\;\@]*?(?(?=\#\{\$)\#\{\$.*?(?=\})))(?(?=\s+)\s+))(?(?=\}|\{\$)\0)(?=\{))|(?=\;)\;(?(?=\s+)\s+)([^\%\;\@\{\}]*?(?(?=\#\{\$)\#\{\$.*?(?=\})\}))(?(?=\s+)\s+)(?=\{)|(^[^\%\;\@]*?(?(?=\#\{\$)\#\{\$.*?(?=\})))(?=\{))');
define('REGEX_ID_SCSS', '(?(?=\#\w+)\#([\w\-]+)(?:(?=\,)|(?=\:)|(?=\s+)|(?=\.)|(?=\+)|(?=\~)|(?=\>)|(?=\[)|\#[\w\-]+|)|\0)');
define('REGEX_CLASS_SCSS', '(?(?=\.\w+)\.([\w\-]+)(?:(?=\,)|(?=\:)|(?=\s+)|(?=\#)|(?=\+)|(?=\~)|(?=\>)|(?=\[)|\.[\w\-]+|)|\0)');
define('REGEX_TAG_SCSS', '(?(?=\#\w+)\#\w+|\0)(*SKIP)(*FAIL)|(?(?=\.\w+)\.\w+|\0)(*SKIP)(*FAIL)|(?(?=\-\w+)\-\w+|\0)(*SKIP)(*FAIL)|(?(?=\_\w+)\_\w+|\0)(*SKIP)(*FAIL)|(?(?=(?:\:lang\b|\:nth-child\b|\:nth-last-child\b|\:nth-last-of-type\b|\:nth-of-type\b)\((?(?=\s+)\s+).+?(?=\))\))(?:\:lang\b|\:nth-child\b|\:nth-last-child\b|\:nth-last-of-type\b|\:nth-of-type\b)\((?(?=\s+)\s+).+?(?=\))\)|\0)(*SKIP)(*FAIL)|(?(?=\:\w+)\:\w+|\0)(*SKIP)(*FAIL)|(?(?=\[(?(?=\s+)\s+)\w+)\[(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(?(?=\=(?(?=\s+)\s+)\w+)\=(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(?(?=\"(?(?=\s+)\s+)\w+)\"(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(?(?=\'(?(?=\s+)\s+)\w+)\'(?(?=\s+)\s+)\w+|\0)(*SKIP)(*FAIL)|(\w+)');
// HTML
define('REGEX_ID_HTML', 'id\b(?(?=\s+)\s+)\=(?(?=\s+)\s+)\"(?(?=\s+)\s+)([\w+\-]+)(?(?=\s+)\s+)(?=\")');
define('REGEX_CLASS_HTML', 'class\b(?(?=\s+)\s+)\=(?(?=\s+)\s+)\"(?(?=\s+)\s+)(.*?)(?(?=\s+)\s+)(?=\")');
define('REGEX_TAG_HTML', '<(?(?=\s+)\s+)(\w+)');
// JS
define('REGEX_GET_ID_JS', '(?:getElementById\b)\(.*?[\"\'](?(?=\s+)\s+)([\w+\-]+)(?(?=\s+)\s+)[\"\']');
define('REGEX_GET_CLASS_JS', '(?:getElementsByClassName\b)\(.*?[\"\'](?(?=\s+)\s+)([\w+\-]+)(?(?=\s+)\s+)[\"\']');
// JQUERY
define('REGEX_ADD_REM_HASCLASS_JQUERY', '(?:\.addClass\b|\.hasClass\b|\.removeClass\b)\((?(?=\s+)\s+)[\"\'](?(?=\s+)\s+)(.+?)(?=\"|\')[\"\']\)');
define('REGEX_SELECTORS_JQUERY', '(?:\$)\((?(?=\s+)\s+)[\"\'](?(?=\s+)\s+)(?(?=[a-zA-Z0-9\*\s\-\:\>\[\]\~\+\*\,\|\=\$\^\'\"\_\d]+?)[a-zA-Z0-9\*\s\-\:\>\[\]\~\+\*\,\|\=\$\^\'\"\_\d]+?)(?=\#|\.)(?|([\#\.a-zA-Z0-9\*\s\-\:\>\[\]\~\+\*\,\|\=\$\^\"\'\_\d\(\)]+?))(?(?=\s+)\s+)[\"\'](?(?=\s+)\s+)\)');
define('REGEX_ID_SELECTORS_JQUERY', '\#[\w\-]+');
define('REGEX_CLASS_SELECTORS_JQUERY', '\.[\w\-]+');


// define patterns for namespaceCSS
define('REGEX_SVG_ALL', '<svg[^>]*?[^>]*?>([^<]*(?(?!<\/svg>)<))*<\/svg>');
