<?php

$foo='Hello World @ '.strftime('%c');

#$osW_TemplateLoader = new osWFrame\Core\TemplateLoader();
#$osW_TemplateLoader->addTemplateCSSFile('head', 'data/resources/content/layout.css');
#$osW_TemplateLoader->addTemplate($osW_Template);


$osW_Template->setVarAsCopy('bar', $foo);


$osW_Template->buildhrefLink('test', 'a=1&b=2&c=3#Test');

?>