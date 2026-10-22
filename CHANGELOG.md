# Changelog: Product Questions for Magento 1 / OpenMage

All releases of this extension, newest first. This repository contains the latest release, tagged [`1.6.0`](https://github.com/PluginCompany/magento1-product-questions/releases/tag/1.6.0); earlier versions are listed for reference and have no tag.

## 1.6.0 — 2019-09-20

- Added e-mail header / footer theme to question and answer notification
- Added default 'Answered by' field in system configuration
- Fixed issue where product link in notification e-mail didn't work when 'Add store code to URLs' is enabled in multi-store config

## 1.5.0 — 2018-07-21

- Ask a question link is now displayed as a button
- All inputs now have the input-text css class, which is used in many themes including the RWD theme

## 1.4.0 — 2018-05-17

- New system config option which allows enabling / disabling private questions
- Fix for issue where the total questions count was incorrect when there were multiple pages of questions

## 1.3.1 — 2017-12-24

- Code improvements for Magento Marketplace
- Small admin improvement

## 1.3.0 — 2017-03-10

- Added reCaptcha to front-end product question form
- Removed questions block from product review page

## 1.2.3 — 2016-06-28

- Added out of the box support for Ultimo / Infortis theme
- Added Magento template mailer support for custom mailer extensions like Mandrill

## 1.2.2 — 2016-05-01

- Fixed bug related to deleting multiple questions at once in the back-end
- Fixed empty product question submission bug
- Fixed e-mail notification sending issue
- Fixed permissions ACL issue
- Fixed issue regarding back button in admin interface

## 1.2.1 — 2016-03-30

- Template variables now parsed in front-end questions and answers
- Fixed minor CSS styling issue related to product questions widget

## 1.2.0 — 2015-11-27

- Added front-end product question sort order option
- Set default back-end product question sort order to descending by date
- Set reply to question recipient to customer e-mail address
- Fixed CSS and thumbs display issue on subsequent product question pages
- Made upvote and downvote numbers clickable as well
- Moved CSS and JS to layout XML
- Removed URLs from JS so Google doesn’t try to crawl
- Fixed compatibility issue with older PhP versions
- Added German translation support

## 1.1.3 — 2015-11-11

- Made compatible with SUPEE-6788

## 1.1.2 — 2015-11-09

- Fixed headers already sent bug (in case of incorrect default server charset)

## 1.1.1 — 2015-10-18

- Completely moved ACL and menu section to adminhtml.xml

## 1.1.0 — 2015-09-24

- Added Product Question tab to back-end and front-end customer account
- Added pre-filled name and e-mail to front-end Product Question panel for logged in customers

## 1.0.5 — 2015-07-09

- Small bug fix

## 1.0.4 — 2015-05-11

- Minor changes to question mail system configuration

## 1.0.3 — 2015-05-03

- Added material front-end design
- Added product questions widget
- Added private question check-box to front-end form
- Added question rating functionality (up-vote & down-vote)
- Added WYIWYG editor in admin interface

## 1.0.1 — 2015-05-01

- Changed tab functionality
- Added language CSV

## 1.0.0 — 2014-10-30

- First release of Magento Product Questions extension by Plugin Company.
