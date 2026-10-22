# Integrating Product Questions in a Custom Theme

While our [Product Questions extension](https://plugin.company/magento1-extensions/product-questions.html) support most Magento themes, some custom themes will need a small modification in order to display the Product Questions panel on the product page.

Below you'll find two methods to integrate Product Questions in a custom theme. Please check out [this tutorial](./10-integrating-product-questions-in-the-ultimo-theme.md) if you're a Ultimo theme user!

## Adding Product Questions to a CMS Block

If your custom theme supports CMS static blocks in a tab or any other location on the product page, you can integrate Product Questions by adding a new CMS block to the product page.

Copy-paste the following code snippet in the CMS block content:

```text
{{block type="productquestions/frontend_questions" template="plugincompany/questions.phtml"}}
```

After saving the CMS block you might have to refresh the store cache to display the Product Questions block on your front-end product pages.

## Adding Product Questions to the Product View Template

You can also add the Product Questions block directly to the product view.phtml template, which can be found in the following directory of your Magento installation:

`app/design/frontend/YOURPACKAGE/YOURTHEME/template/catalog/product/view.phtml`

Copy-paste the following code snippet in the product view template and save the .phtml file to add the Product Questions block to your product page:

```php
<?php echo $this->getLayout()->createBlock("productquestions/frontend_questions")->setTemplate("plugincompany/questions.phtml")->toHtml(); ?>
```

---

[Documentation index](./README.md) · [README](../README.md) · [Online version](https://plugin.company/knowledge-base/docs/magento-product-questions/tutorials/integrating-product-questions-in-a-custom-theme/)
