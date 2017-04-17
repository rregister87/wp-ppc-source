# wp-ppc-source
When running a PPC campaign, it's common for the destination URL of your site to receive various query strings indicating the source. Consider the following:
> url.com?utm_source=1234.

Unfortunately query strings do not persist as the user traverses the site and leaves the page, so you lose the ability to track conversion sources.

This plugin addresses that problem by converting query strings to PHP session variables, accessible for use as hidden form input fields using the included shortcode. A function to pass session variables to the JavaScript console has also been included for debugging purposes.

## Example Usage
```
[wp_ppc term="utm_source" input_name="my_input_name"]
```
The code above would output the following HTML with the value of the "utm_source" session variable:
```
<input name="my_input_name" type="hidden" value="">
```
