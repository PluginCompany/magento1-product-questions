# Integrating Product Questions in the Ultimo Theme

If you're using the Ultimo theme for Magento, you can add the Product Questions panel to your product pages following the instructions below.

- Navigate to **CMS > Static Blocks** in the top Admin Panel menu and open the 'Product tab2' block.
- Copy-paste the following code snippet in the block content editor:
- ```
  {{block type="productquestions/frontend_questions" template="plugincompany/questions.phtml"}}
  ```
- Save the CMS block and go to **System > Permissions > Block** in the Admin Panel menu.
- Click 'Add New Block' and enter the following description in the 'Block Name' field: *productquestions/frontend_questions*
- Enable the 'Is Allowed' option and click the 'Save Block' button in the top right corner of the page to integrate Magento Product Questions in the Ultimo theme product pages.

---

[Documentation index](./README.md) · [README](../README.md) · [Online version](https://plugin.company/knowledge-base/docs/magento-product-questions/tutorials/integrating-product-questions-in-the-ultimo-theme/)
