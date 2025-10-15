# BizPress

BizPress is Bizink's collection of WordPress plugins.

## BizPress Pliguns

### Client Plugins

#### Main Plugin
- [BizPress](https://github.com/BizInk/bizpress-client)

#### Content Plugins
- [BizPress - Blogs](https://github.com/BizInk/bizpress-blogs)
- [BizPress - Calculators](https://github.com/BizInk/bizpress-calculators)
- [BizPress - Accounting Glossary](https://github.com/BizInk/bizpress-accounting-glossary)
- [BizPress - Business Terms Glossary](https://github.com/BizInk/bizpress-business-terms-glossary)
- [BizPress - Key Dates](https://github.com/BizInk/bizpress-key-dates)

- [BizPress - Payroll](https://github.com/BizInk/bizpress-payroll)
- [BizPress - Resources](https://github.com/BizInk/bizpress-resources)
- [BizPress - Business Resources](https://github.com/BizInk/bizpress-business-resources)

- [BizPress - Xero Resources](https://github.com/BizInk/bizpress-xero-resources)
- [BizPress - Quickbooks Resources](https://github.com/BizInk/bizpress-quickbooks-resources)
- [BizPress - MYOB Resources](https://github.com/BizInk/bizpress-myob-resources)
- [BizPress - Sage Resources](https://github.com/BizInk/bizpress-sage)
- [BizPress - Freshbooks Resources](https://github.com/BizInk/bizpress-freshbooks-resources)

- [BizPress - XPM](https://github.com/BizInk/bizpress-xpm)

### Client Site Plugins

- [BizPress - Luca](https://github.com/BizInk/bizpress-luca) - Luca 1 sites only
- [BizPress - Luca 2](https://github.com/BizInk/bizpress-luca-2) - Luca 2 sites only

### Server Plugins

- [Bizpress - WC](https://github.com/BizInk/bizink-wc) - Validates User logins this is on the Bizink site
- [Bizpress - Publisher](https://github.com/BizInk/bizink-publisher) - The content publisher on bizinkcontent.com

### Other BizPress

- [BizPress - Chrome Addon](https://github.com/BizInk/bizpress-chrome-plugin)

## How to update the plugin

1. Have the latest version of the plugin in the master/main branch
    - Note this should contain the latest version number in the plugin file ```e.g. bizink-clinet.php```
2. Make sure the **Readme.txt** file is up to date - This file is written in **WordPress Markdown** see Readme.txt section below
    - This has a Version item and a Stable Tag item **both** should be the new verson number e.g. 1.0
    - This file also has a Change Log included. Update this as this file is used by wordpress to show changes on the plugin update section
3. Create a new Release in the releases tab. **Important:** Make sure the tag is the new version number e.g. 1.0
    - If the release is marked as *Pre Release* the plugin updater will ignore it.
    - We should also put what has changed in the release notes but these would be for our benefit and not used by the plugin.

### Readme.txt

This file is written in **WordPress Markdown** this is different to GitHub Markdown and is also different to the Readme.md file. It is required for the updater to work. Update the details in this file on the master branch then add a new release with the github tag in order to post a new version of the plugin. [Follow this example https://wordpress.org/plugins/readme.txt](https://wordpress.org/plugins/readme.txt)

### Tags

Using the master branch create a tag with the name being the version number of the release. **Note:** It should be the same version number in the plugin files and the readme.txt file