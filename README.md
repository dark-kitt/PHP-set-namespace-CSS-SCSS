# PHP set namespace CSS/SCSS

A small php script, to set namespace for CSS/SCSS.

## Usage

Call the **`namespaceCSS::find_all_IDs_CLASSes_TAGs(( HTML_content <= string ), ( SCSS_content <= string ), ( JS_Content <= string ));`** function to get all ids, classes and tags. This function requires strings of each whole content, this means you have to merge the content before, like in the example below. Afterwards call the **`namespaceCSS::set_namespace(( all_IDs_CLASSes_TAGs <= array ), ( HTML_content <= string ), ( SCSS_content <= string ), ( JS_Content <= string ));`** function for each file, to return the new content with the namespace.

## Note!

Matched only `getElementById`, `$(#id)`, `getElementsByClassName`, `addClass`, `hasClass`, `removeClass` and `$(.class)` in the JavaScript files.

## Example:

    require 'namespaceCSS.php';

    // glob all is normally just a star without the backslash//
    $all_HTML_files = glob( 'test/html/\*.php' );
    $all_SCSS_files = glob( 'test/scss/\*.scss' );
    $all_JS_files = glob( 'test/js/\*.js' );

    $all_HTML_content = '';
    $all_SCSS_content = '';
    $all_JS_content = '';
    foreach ( $all_HTML_files as $html_file )
    {
        $all_HTML_content .= file_get_contents($html_file);
    }
    foreach ( $all_SCSS_files as $scss_file )
    {
        $all_SCSS_content .= file_get_contents($scss_file);
    }
    foreach ( $all_JS_files as $js_file )
    {
        $all_JS_content .= file_get_contents($js_file);
    }

    $store_all_IDs_CLASSes_TAGs = namespaceCSS::find_all_IDs_CLASSes_TAGs($all_HTML_content, $all_SCSS_content, $all_JS_content);
    $namespace = 'test_namespace__';

    foreach ( $all_HTML_files as $html_file )
    {
        $new_HTML_content = namespaceCSS::set_namespace($store_all_IDs_CLASSes_TAGs, file_get_contents($html_file), null, null, $namespace);
        file_put_contents($html_file, $new_HTML_content);
    }
    foreach ( $all_SCSS_files as $scss_file )
    {
        $new_SCSS_content = namespaceCSS::set_namespace($store_all_IDs_CLASSes_TAGs, null, file_get_contents($scss_file), null, $namespace);
        file_put_contents($scss_file, $new_SCSS_content);
    }
    foreach ( $all_JS_files as $js_file )
    {
        $new_JS_content = namespaceCSS::set_namespace($store_all_IDs_CLASSes_TAGs, null, null, file_get_contents($js_file), $namespace);
        file_put_contents($js_file, $new_JS_content);
    }
