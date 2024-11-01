=== WP Tags to Trendfo ===
Contributors: glenstansberry
Tags: tags,trendfo,search,stats, news, social media, twitter, blogs, videos, photos, flickr
Requires: 2.3.0-2.6.3+
Stable tag: Trunk

This plugin will create Trendfo tags from native Wordpress tags

== Installation ==

1. Upload the full directory into your wp-content/plugins directory
2. Activate it in the Plugin options

== Options ==

1. Text that appears to the left of the tags can be adjusted by 
   changing the "Trendfo Tags label" field.

2. Enabling the "Open Trendfo Link in new window?" option will cause
   the generated links to open in a new window.

3. Enabling the 'Add rel="nofollow" to generated links?' option will
   cause the geenerated links to have nofollow added to the rel 
   attribute on the technorati links (they already have a 'tag' 
   attribute).
== Advanced Usage ==

If you wish to include the Trendfo tags in a different place on a page,
you can disable the "Include tags in post footer?" option and modify your
blogs theme to echo the output of the 'trendfotags_get_tags_links();' 
function inside the wordpress 'loop'.


== Frequently Asked Questions == 

= Works it with all WordPress versions? =

This version works with WordPress 2.3.0 and better.  It's dependent on the new
Wordpress tagging feature.

== Credits ==

This plugin was derived very heavily from the WP to Technorati Tags plugin (http://wordpress.org/extend/plugins/wp-tags-to-technorati/) by Daniel Gibbs. 

== License ==

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

