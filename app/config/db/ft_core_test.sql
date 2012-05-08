-- MySQL dump 10.13  Distrib 5.5.19, for osx10.6 (i386)
--
-- Host: localhost    Database: frontendtest
-- ------------------------------------------------------
-- Server version	5.5.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CoreTest`
--

DROP TABLE IF EXISTS `CoreTest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CoreTest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) NOT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `heading` varchar(2000) NOT NULL,
  `description` longtext,
  `extended_description` longtext,
  `more_details` longtext,
  `resources` longtext,
  `weight` smallint(6) DEFAULT NULL,
  `notes` varchar(2000) DEFAULT NULL,
  `run_by_default` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `print_line_numbers` tinyint(1) DEFAULT NULL,
  `print_details` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CoreTest`
--

LOCK TABLES `CoreTest` WRITE;
/*!40000 ALTER TABLE `CoreTest` DISABLE KEYS */;
INSERT INTO `CoreTest` VALUES (1,'NotHtml5Doctype','HTML5','Site should be updated to HTML5, the evolving standard','If this is a site under development, versus a retired page of content, you probably want to make it HTML5 instead of %1%. This transition should be as easy as changing the doctype to `<!doctype html>`. \r\n\r\nIf you wish, you may also make the character encoding meta tag shorter. What had looked something like this:\r\n\r\n`<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" />`\r\n\r\nCan be replaced with this:\r\n\r\n`<meta charset=\"utf-8\">`\r\n\r\nAfter you make this change you\'ll want to run [HTML5 validation](http://html5.validator.nu/) against it. Here is a [direct link](http://html5.validator.nu/?doc=%url%) for running HTML5 validation against your site once you change the doctype.',NULL,NULL,NULL,70,'We only want to do this test if there is a doctype and it is first. Otherwise, DoctypeNotFirstElement is triggered.',1,1,0,0,'2007-01-01 00:00:00',NULL),(2,'Html5ElementsWOShim','HTML5','You must use a shim if you use new HTML5 elements and want to support current browsers','The <%new_element%> tag is introduced with HTML5 and is not recognized by Internet Explorer versions less than 9. Therefore it is recommended that you create those elements using JavaScript. There are several ways to populate the HTML5 elements for the browser. %resource_below_message%',NULL,NULL,'http://code.google.com/p/html5shim/',50,NULL,1,1,0,0,'2007-01-01 00:00:00',NULL),(3,'NoDoctype','HTML','No doctype provided','It\'s important to include a doctype declaration in your source so that browsers know what kind of document to expect. If you use the HTML5 doctype -- which we recommend -- this would be the first line of your document:\r\n\r\n`<!DOCTYPE html>`',NULL,NULL,NULL,80,NULL,1,1,0,0,'2007-01-01 00:00:00',NULL),(4,'DoctypeNotFirstElement','HTML','A doctype declaration should appear first in the HTML document','The doctype declaration\'s purpose is to tell browsers (and validation tools) what type of HTML document it is receiving. If you do not provide a doctype, or the doctype is not the first instruction in your document, browsers will render in [quirks mode](http://en.wikipedia.org/wiki/Quirks_mode). If you don\'t want to think about what that means, or maintain a site in quirks mode, we recommend using the HTML5 doctype:\r\n\r\n`<!DOCTYPE html>`\r\n\r\nThis doctype will render pages in \"standards mode\" for most browsers, and \"almost standards mode\" for older versions of Internet Explorer (6 and 7).',NULL,NULL,NULL,90,'We do this test instead of searching for the existence of a doctype, because this kills two birds with one stone. If this test is true, we don\'t want to return the \"nothtml5doctype\" test. Baby steps.',1,1,0,0,'2007-01-01 00:00:00',NULL),(5,'BrokenLink','HTML','%2% broken link%3% found','%1%\r\n%4%',NULL,NULL,NULL,45,NULL,1,1,0,0,'2007-01-01 00:00:00',NULL),(6,'GifsUsed','HTML','GIF images should be replaced with PNGs','%url% has %1% gifs with a total file size of %2%.\r\n\r\nThe image file format PNG was created to improve upon and replace GIF images; they compress better. Also, there are patent issues around using GIFs. We suggest PNG-8 for non-transparent images, PNG-24 for transparent images, and JPEG for photos.',NULL,NULL,NULL,10,'The weight of this issue should increase by the amount of gifs found. for now, there must be at least 3 gifs for us even bother alerting the user.',1,1,0,0,'2007-01-01 00:00:00',NULL),(7,'MultipleScriptsInHead','Script','Javascript should be included at the bottom of the page, not in the head','There is a habit of putting script tags in the head of HTML documents, but scripts usually do not need to be loaded first, and they block content rendering. No more than two scripts can be download and parsed by a browser at once. You have %1% script elements in `<head>`. \r\n\r\nWe suggest putting scripts at the bottom of the page, before the close body tag. For Internet Explorer only, you have the option of adding defer=defer to the script tag.',NULL,NULL,NULL,25,'Modernizer note: It may be better to add the HTML5 shim separately from Modernizr, load Modernizr at the bottom of the page, and see if FOUT is a problem. Also, you might ask yourself if Modernizr is necessary for your site. Modernizr is an excellent way to polyfill HTML5, but if you\'re not really using it you might not want it to block your content.',0,1,0,0,'2007-01-01 00:00:00',NULL),(8,'ManyImagesFlag','HTML','A questionably large number of images (%1%) are used','Images are HTTP requests that slow down rendering and should be minimized. \r\n\r\nUnless your HTML document is something as primitive as an HTML email, images should not be used for layout. Replace corners, spacers, shadows, gradients...etc. with CSS.',NULL,NULL,NULL,8,'Very minor, but may indicate an area the site could optimize',1,1,0,0,'2007-01-01 00:00:00',NULL),(9,'JavascriptInHref','HTML','The JavaScript pseudo-protocol should not be used as the target of a link','The JavaScript pseudo-protocol, \"javascript:\", was found here:\r\n\r\n%1%\r\n\r\nSimply put, links should be links, not javascript. It\'s more appropriate to use the onClick event handler and to apply JavaScript to the click of an element. Better code would be:\r\n\r\n`<a href=\"/current_page.html\" onClick=\"doOnClick()\">`\r\n\r\nHowever, there is no reason for this element to be a link, especially since it behaves more like a button. Use `<span>`, `<div>` or `<button>` and apply a style that sets the cursor to pointer for that element, so that the user gets the visual cue that the element is clickable. \r\n\r\n`<div onClick=\"doOnClick()\" class=\"fauxButton\">`\r\n\r\nWe can improve on this a bit more, but the important short-term goal is replace \"javascript:\" in links.',NULL,'Here\'s [Jeremy Keith\'s simple argument against using the javascript pseudo-protocol](http://domscripting.com/book/sample/):\r\n\r\n^ \"This will work just fine in browsers that understand the javascript: pseudo-protocol. Older browsers, however, will attempt to follow the link and fail. Even in browsers that understand the pseudo-protocol, the link becomes useless if JavaScript has been disabled.\"',NULL,20,NULL,1,1,0,0,'2007-01-01 00:00:00',NULL),(10,'ImgAltAttributeMissing','HTML','Missing img alt attribute','Missing the alt attribute in an image may seem like a small thing, but it is important for accessibility as well as required. You should add alt text to the following:\r\n\r\n%1%',NULL,NULL,NULL,10,NULL,1,0,0,0,'2007-01-01 00:00:00',NULL),(11,'MissingImgHeightOrWidth','HTML','%2% Image%3% should have height and width set','Height and width are not officially required attributes for `<img>`. However, including them guarantees faster content download. That information also helps the browser determine the viewport space for the image, making the rendering visually smoother. We recommend adding the correct pixel dimensions to %2% image%3% found at %url%.\r\n\r\n%1%',NULL,NULL,NULL,18,NULL,1,0,0,0,'2007-01-01 00:00:00',NULL),(12,'BoldTags','HTML','Bold tags do not have much semantic meaning. Replace them with heading level or strong tags','This is a very minor issue. However, if you enclosed text with `<b>` because it is important text, it would be better to use `<strong>`; `<strong>` means important whereas `<b>` means \"visually disctinct from text that surrounds it.\"\r\n\r\nIf the text in question can be considered a headline, it\'s best to use a heading level tag: `<h1> ... <h6>`.\r\n\r\n%1%',NULL,NULL,NULL,5,NULL,1,0,0,0,'2007-01-01 00:00:00',NULL),(13,'ClassNameSameAsHtml5Element','HTML5','A class name that duplicates an HTML element name was found','This usually indicates that the markup can be more semantic. \r\n\r\n%1%',NULL,NULL,NULL,30,NULL,1,0,0,0,'2007-01-01 00:00:00',NULL),(14,'AmpersandUnescapedInLink','HTML','Ampersands (&) in links need to be converted to \"&amp;\" when written in HTML','When we write links in the body of our HTML document we need to convert \"&\" to \"&amp;\". (It is not necessary to do this otherwise: for example, when pasting into emails or browser location bars.) As attribute values for \"src\" and \"href\", plain ampersands are invalid. Unescaped ampersands were found in %2% link%3%.\r\n\r\n%1%\r\n\r\nBrowsers usually interpret links with unescaped ampersands just fine. However, it\'s a bad practice and there are pitfalls. For example, if you wrote a query string like \"?copy1=test&copy2=test\" a browser might interpret this as \"?copy1=test©2.\"',NULL,NULL,NULL,9,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(15,'CaseSensitivePublicIdIncorrect','HTML','Case-sensitive formal public id in doctype incorrect','This:\r\n\r\n`%1%`\r\n\r\nshould be:\r\n\r\n`%2%`',NULL,NULL,NULL,20,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(16,'TitleElementMissing','HTML','You must include a title element in your HTML','Across all types of HTML documents, the title element is the only element that is always required.',NULL,'Side note: in HTML5 you may exclude the title element in certain other cases such as sending an HTML email when the subject is provided. Here is [more info on minimum HTML document requirements] (http://mathiasbynens.be/notes/minimal-html)',NULL,NULL,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(17,'RequiredXhtmlElementNotFound','XHTML','When sending a page as XHTML, certain elements are required. %url% is missing at least one of these elements','Recommended suggestion: make the page HTML5 by replacing the doctype with \r\n\r\n`<!DOCTYPE html>`\r\n\r\nOtherwise add the missing XHTML element(s): \r\n\r\n`%1%%2%%3%%4%`',NULL,NULL,NULL,80,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(18,'LargeSiteNoSitemap','HTML','In this case, it\'s probably a good idea to provide a sitemap.xml file','From [the Wikipedia article on Site_map](http://en.wikipedia.org/wiki/Site_map):\r\n\r\n\"a Sitemap is still the best insurance for getting a search engine to learn about your entire site\"',NULL,NULL,NULL,15,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(19,'DefaultBodyCopyIllegible',NULL,'Body copy is difficult to read at current font size','Suggestion: increase the body copy font size or consider using a more legible font for small text. A general rule for digital type is that sans serif fonts are more legible than serif fonts at small sizes.',NULL,NULL,NULL,50,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(20,'FontFaceNotBulletproof',NULL,'Font face included but not bulletproof','It is possible to support a custom web font for all modern browsers including Internet Explorer. Here\'s [Paul Irish\'s bulletproof syntax](http://paulirish.com/2009/bulletproof-font-face-implementation-syntax/).',NULL,NULL,NULL,53,'manual test for now',1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(21,'PercentUnusedStyles',NULL,'Styles created for unused elements','Suggestion: remove unused styles.',NULL,NULL,NULL,30,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(22,'MetadataContentEmpty','HTML','Metadata content is empty','If the content is empty, you might as well remove the meta tag.\r\n\r\n%1%',NULL,NULL,NULL,3,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(23,'DoubleSlashesInUrl','HTML','Redundant slashes in URL.','This will cause a problem in X browser.',NULL,NULL,NULL,12,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(24,'ScriptBeforeCss','HTML','Stylesheet%2% included after script%3% in head','To allow your site to render progressively and appear more quickly -- in other words, to make your site faster -- move CSS links to the top of the document head, before any scripts. The Yahoo! Developer Network describes [this suggestion in more detail](http://developer.yahoo.com/performance/rules.html#css_top). \r\n\r\n%1%',NULL,NULL,NULL,60,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(25,'DuplicateElementsWhichShouldBeUnique','HTML','The %1% element cannot appear twice in an HTML document','%2%',NULL,NULL,NULL,80,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(26,'TitleMayOnlyBeChildOfHead','HTML5','The title element may only appear within the head of the HTML document.','Your title is nested under %1%',NULL,NULL,NULL,70,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(27,'InvalidElementInHead','HTML5','Element %1% not allowed in head',NULL,NULL,NULL,NULL,60,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(28,'ImageHasSuspiciousNameFlag','CSS3','Image name indicates site design was built in an outdated fashion (pre-CSS3)',NULL,NULL,NULL,NULL,7,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(29,'HasNestedTables','HTML','Table elements should be reserved for tabular data, and not used to build site layout','We suggest reducing and simplifying your front-end code with semantic HTML5 markup.',NULL,NULL,NULL,5,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(30,'FaviconMissing','HTML','Favicon not included','Favicons are graphics that appear in browsers next to location bars, bookmarks and tabs. They improve the user experience and they are easy to make and add. Just create 16x16 or 32x32 .gif, .png or .ico\\* image, upload it to your server, and point to it using the following tag included in `<head>`:\r\n\r\n`<link rel=\"icon\" type=\"image/x-icon\" href=\"/path/to/favicon\">`\r\n\r\nYou can also put an ICO file format graphic called \"favicon.ico\" in your root web directory - without needing to include the tag above - but this is [not the current recommended method](http://www.w3.org/2005/10/howto-favicon). \r\n\r\n\\*To create a favicon in the .ico format, use an [online tool](http://www.favicon.cc/) or install a [Photoshop plugin](http://www.telegraphics.com.au/sw/).',NULL,NULL,NULL,20,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(31,'RequiredTagAttributePairMissing','HTML','%2% is a required attribute for the %1% element',NULL,NULL,NULL,NULL,35,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(32,'DeprecatedElement','HTML','\"%1%\" is a deprecated HTML4 element and should be replaced with %2%','%3%',NULL,NULL,NULL,25,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(33,'CssOutsideOfHead','CSS','Styles should be included in <head>','We found linked or inline styles outside of the head tag:\r\n\r\n%1%\r\n\r\nIf you put your stylesheets at the top of your document, within the `<head>` tag, your web site will appear to be faster because it will render progressively. Here\'s [more info from Yahoo!](http://developer.yahoo.com/performance/rules.html#css_top).',NULL,NULL,NULL,30,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(34,'ManyInlineStylesFlag','HTML','Inline styles should be avoided; %1% inline styles detected','While you may have a good reason to write styles inline using the \"style\" attribute, generally it is not a good practice. It makes bloated code, styles are not re-usable, styling has too much specificity...etc. We only make this suggestion when the sum of inline styles has reached a suspicious threshold (>%2%), and we suggest identifying common styles and them in a style sheet. When you need to update or change your styles, you will thank us.',NULL,NULL,NULL,20,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(35,'BrokenImage','HTML','%2% broken image%3% found','%1%\r\n%4%',NULL,NULL,NULL,31,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(36,'GraphicsOfText',NULL,'Text should not be made with graphics','There are many reasons why it is a bad idea to make graphics of text. Graphics make pages slow; by using graphics you are adding http requests and files that need to be downloaded. Also, graphics cannot be changed as easily as text; you must have image editing software, the font installed on your system, recall the previous optimization settings, export files, upload files to web servers...etc. Graphics of text can be very difficult to change; impossible perhaps, if originally produced by an external entity.\r\n\r\nThere are few remaining reasons why you would want to make the extra effort of converting text in graphics. One is branding. Does your brand require you to use a specific font? In that case we suggest using the CSS feature @font-face which is supported by all modern browsers. Font files are heavy -- about 50K -- but if you are required to use a custom font, it is usually the best solution. Here are instructions for [a bulletproof implementation of font-face](http://paulirish.com/2009/bulletproof-font-face-implementation-syntax/).',NULL,'Once loaded, a web font can be reused anywhere on your site without a performance hit.',NULL,80,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(37,'PossibleStaleCopyrightEndYear','Content','Copyright end year is not current year','If this web site continues to be developed, you may want to update the copyright notice.\r\n\r\n%1%',NULL,NULL,NULL,9,'making low priority because it is likely that this triggered on accident. You don\'t need to check it manually, but only include it if there are few other issues.',0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(38,'CommentsBeforeDoctype',NULL,'Comments before doctype can make some browsers render in quirks mode',NULL,NULL,NULL,NULL,9,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(39,'HasHtml5ElementWODoctype','HTML5','If you elements introduced with HTML5, you should used the HTML5 doctype','%1% is an HTML5 element.\r\n\r\n%2%',NULL,NULL,NULL,30,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(40,'HasDocumentWrite','HTML','document.write() should not be used',NULL,NULL,NULL,NULL,9,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(41,'DeprecatedAttribute','HTML','Deprecated attribute(s) found (%3% instance%2%)','%1%\r\n\r\nWe suggest you use an HTML validator to discover and replace deprecated attributes. Here are a couple options:\r\n\r\n[W3C Markup Validation Service](http://validator.w3.org/)\r\n\r\n[Validator.nu (X)HTML5 Validator](http://html5.validator.nu/)',NULL,NULL,NULL,11,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(43,'LargeScriptNotMinified','Filedata','Minify %2% script%3% to improve web site performance','The following  large (>5KB) script%3% should have whitespace removed:\r\n\r\n%1%',NULL,NULL,NULL,16,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(44,'LargeStylesheetNotMinified','Filedata','Minify %2% style sheet%3% to improve web site performance','The following large (>5KB) stylesheet%3% should have whitespace removed: \r\n\r\n%1%',NULL,NULL,NULL,16,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(45,'IsHtml5HasXhtmlCloseTags','HTML5','XHTML close tags \" />\" can be removed from HTML5 document','They are harmless, but if you\'d like to reduce and clean up your code a bit, you can remove XHTML close tags from %1% elements.',NULL,NULL,NULL,3,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(46,'DuplicateId','HTML','Duplicate ID%2% found','IDs must be unique to an HTML document. Please correct the following duplicate ID%2%:\r\n\r\n%1%',NULL,NULL,NULL,70,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(47,'IncorrectImagePixelSize','Filedata','Height and/or width set incorrectly for %2% image%3%','The height and width attributes of the `<img>` element should match the pixel dimensions of the graphic, or the graphic should be resized to match the height and width set in the HTML. \r\n\r\nIf a graphic is bigger than the assigned image attributes, you\'re showing the user less than they are downloading. If the graphic is smaller than what is assigned, then the image will scale up and look pixelated. In any case, when web browsers resize images, it does not look ideal.\r\n\r\n%1%',NULL,NULL,NULL,20,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(48,'LocalJQueryLink','Script','Use shared, hosted JQuery script for the caching benefits',NULL,NULL,NULL,NULL,15,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(49,'UnusuallyLargeFile','Filedata','Site loads an unusually large resource','We suggest you consider reducing the file size%2% of the following large resource%2%:\r\n\r\n%1%',NULL,NULL,NULL,35,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(50,'ScriptTagHasType','Script','Type attribute in script element is unnecessary: javascript is always assumed','This is a trivial issue, but if you\'d like to reduce and clean up your code a bit, you can remove \"type=text/javascript\" from %1% elements.',NULL,NULL,NULL,3,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(51,'HasDeeplyNestedTables','HTML','Tables nested three or more levels','The `<table>` tag should be reserved for tabular data. Table elements nested three or more levels may be an indication that the web site layout was built with tables, an outmoded method. We suggest reducing and simplifying your front-end code with semantic HTML5 markup.',NULL,NULL,NULL,30,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(52,'DuplicateResourceIncluded','Content','%2% file included multiple times','%1%',NULL,NULL,NULL,40,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(53,'BrokenLinkDiagnostics','HTML','exploring slow broken link tests',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(54,'ScriptsInHeadTotalFilesizeLarge','Script','Large total file size of script%2% linked in <head>','%1% script%2% in `<head>` total %3%KB. Consider [putting scripts at the bottom of the page](http://developer.yahoo.com/performance/rules.html#js_bottom).',NULL,NULL,NULL,70,NULL,1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(55,'CssInHeadTotalFilesizeLarge','CSS','Large total file size of stylesheet%2% linked in <head>','%1% stylesheets%2% in `<head>` total %3%KB. Consider optimizing and reducing css.',NULL,NULL,NULL,28,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(56,'flashContentWithoutSubstitute','HTML5','Flash content found without X alternative','Flash content does not work on iOS...',NULL,NULL,NULL,20,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(57,'xBangImportant','CSS','The atomic !important keyword makes web site styles difficult to work with','You have %1% instances of !important.',NULL,NULL,NULL,8,NULL,0,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL),(58,'BrokenResource','HTML','%2% broken resource%3% found','%1%\r\n%4%',NULL,NULL,NULL,30,'IMPORTANT NOTE: I make this test the random things: not img or a href for now -- those have separate tests because they are so big.',1,NULL,NULL,NULL,'2007-01-01 00:00:00',NULL);
/*!40000 ALTER TABLE `CoreTest` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-05-07 20:28:35