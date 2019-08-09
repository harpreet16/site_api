# Site Api

The Drupal 8 module for site api key adding feature and json reponse of a node on the url path_to_site/page_json/[siteapikey]/[node_id]

## Installation

This module uses composer to manage dependencies. To install from this repository:

Clone the module into your Drupal installation:

`git clone git@github.com:backupmigrate/backup_migrate_drupal.git /path/to/site/modules/backup_migrate`

Install the module as usual using Drush:

`drush en site_api`

## Testing steps

Go to site settings url `/admin/config/system/site-information`

Enter the site api key value

Check against the url `http://default_url/page_json/[siteapikey]/[node_id]`