# PHP set namespace CSS/SCSS

A small php script, to set namespace for CSS/SCSS.


## Note!

Matched only `getElementById`, `$(#id)`, `getElementsByClassName`, `addClass`, `hasClass`, `removeClass` and `$(.class)` in the JavaScript files.

## Usage

    require 'regex.php';
    require 'namespaceCSS.php';

    // if $selectors is null the function search for selectors with the given directory and file paths
    // or place the content in the array (e.g. ['cnt', 'cnt', etc.]) but don't mix content and directories
    $NS_content = new namespaceCSS(
        $selectors,
        [$html],
        [$scss],
        [$js],
        $directory
    );
