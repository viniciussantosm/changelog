# BIS2BIS Changelog
## First Steps:
1- Add the following code to Dashboard index.phtml to show the module phtml.
```
<?php if(Mage::helper("changelog/config")->isAllowed()): ?>
    <div class="entry-edit" style="margin-top: 1em;">
        <div class="entry-edit-head"><h4><?php echo $this->__('Last news') ?></h4></div>
        <fieldset class="np"><?php echo $this->getChildHtml('changelog'); ?></fieldset>
    </div>
<?php endif; ?>
```
2- Add the following code to the composer.json:
``` 
"require": {
        "viniciussantosm/changelog_module": "master"
}
"repositories": {
        "changelog_module": {
            "type": "vcs",
            "url": "git@github.com:viniciussantosm/changelog_module.git"
        },
}
```
3- Run composer update
