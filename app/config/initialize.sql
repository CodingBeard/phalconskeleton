-- ---
-- Table 'users'
-- 
-- ---

DROP TABLE IF EXISTS `users`;
		
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(255) NULL DEFAULT NULL,
  `lastName` VARCHAR(255) NULL DEFAULT NULL,
  `DoB` DATE NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'formentrys'
-- 
-- ---

DROP TABLE IF EXISTS `formentrys`;
		
CREATE TABLE `formentrys` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL DEFAULT NULL,
  `user_id` INT(11) NULL DEFAULT NULL,
  `form_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'looseforms'
-- 
-- ---

DROP TABLE IF EXISTS `looseforms`;
		
CREATE TABLE `looseforms` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `user_id` INT(11) NULL DEFAULT NULL,
  `private` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'formdatas'
-- 
-- ---

DROP TABLE IF EXISTS `formdatas`;
		
CREATE TABLE `formdatas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `formentry_id` INT(11) NULL DEFAULT NULL,
  `field_id` INT(11) NULL DEFAULT NULL,
  `value` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'formfields'
-- 
-- ---

DROP TABLE IF EXISTS `formfields`;
		
CREATE TABLE `formfields` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `form_id` INT(11) NULL DEFAULT NULL,
  `fieldKey` VARCHAR(255) NULL DEFAULT NULL,
  `fieldName` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'audits'
-- 
-- ---

DROP TABLE IF EXISTS `audits`;
		
CREATE TABLE `audits` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `modelName` VARCHAR(255) NULL DEFAULT NULL,
  `row_id` INT(11) NULL DEFAULT NULL,
  `user_id` INT(11) NULL DEFAULT NULL,
  `ip` VARCHAR(255) NULL DEFAULT NULL,
  `type` CHAR(2) NULL DEFAULT NULL,
  `date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'permissionroles'
-- 
-- ---

DROP TABLE IF EXISTS `permissionroles`;
		
CREATE TABLE `permissionroles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `permission_id` INT(11) NULL DEFAULT NULL,
  `role_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'roles'
-- 
-- ---

DROP TABLE IF EXISTS `roles`;
		
CREATE TABLE `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `level` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'userroles'
-- 
-- ---

DROP TABLE IF EXISTS `userroles`;
		
CREATE TABLE `userroles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL DEFAULT NULL,
  `role_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'auditfields'
-- 
-- ---

DROP TABLE IF EXISTS `auditfields`;
		
CREATE TABLE `auditfields` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `audit_id` INT(11) NULL DEFAULT NULL,
  `fieldName` VARCHAR(255) NULL DEFAULT NULL,
  `oldValue` MEDIUMTEXT NULL DEFAULT NULL,
  `newValue` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'permissions'
-- 
-- ---

DROP TABLE IF EXISTS `permissions`;
		
CREATE TABLE `permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `module` VARCHAR(255) NULL DEFAULT NULL,
  `controller` VARCHAR(255) NULL DEFAULT NULL,
  `action` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'usertokens'
-- 
-- ---

DROP TABLE IF EXISTS `authtokens`;
		
CREATE TABLE `authtokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL DEFAULT NULL,
  `issued` DATETIME NULL DEFAULT NULL,
  `expires` DATETIME NULL DEFAULT NULL,
  `type` VARCHAR(255) NULL DEFAULT NULL,
  `token` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'logins'
-- 
-- ---

DROP TABLE IF EXISTS `logins`;
		
CREATE TABLE `logins` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `ip` VARCHAR(255) NULL DEFAULT NULL,
  `attempt` INT(11) NULL DEFAULT NULL,
  `success` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'emailchanges'
-- 
-- ---

DROP TABLE IF EXISTS `emailchanges`;
		
CREATE TABLE `emailchanges` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `authtoken_id` INT NULL DEFAULT NULL,
  `date` DATETIME NULL DEFAULT NULL,
  `oldEmail` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'navbars'
-- 
-- ---

DROP TABLE IF EXISTS `navbars`;
		
CREATE TABLE `navbars` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'navlinks'
-- 
-- ---

DROP TABLE IF EXISTS `navlinks`;
		
CREATE TABLE `navlinks` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `navbar_id` INT NULL DEFAULT NULL,
  `label` VARCHAR(255) NULL DEFAULT NULL,
  `level` TINYINT NULL DEFAULT NULL,
  `link` VARCHAR(255) NULL DEFAULT NULL,
  `parent_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'pages'
-- 
-- ---

DROP TABLE IF EXISTS `pages`;
		
CREATE TABLE `pages` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `standalone` TINYINT NULL DEFAULT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `template` VARCHAR NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'contents'
-- 
-- ---

DROP TABLE IF EXISTS `contents`;
		
CREATE TABLE `contents` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `ordering` INT NULL DEFAULT NULL,
  `width` TINYINT NULL DEFAULT NULL,
  `offset` TINYINT NULL DEFAULT NULL,
  `content` MEDIUMTEXT NULL DEFAULT NULL,
  `page_id` INT NULL DEFAULT NULL,
  `parent_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `formentrys` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `formentrys` ADD FOREIGN KEY (form_id) REFERENCES `looseforms` (`id`);
ALTER TABLE `looseforms` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `formdatas` ADD FOREIGN KEY (formentry_id) REFERENCES `formentrys` (`id`);
ALTER TABLE `formdatas` ADD FOREIGN KEY (field_id) REFERENCES `formfields` (`id`);
ALTER TABLE `formfields` ADD FOREIGN KEY (form_id) REFERENCES `looseforms` (`id`);
ALTER TABLE `audits` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `permissionroles` ADD FOREIGN KEY (permission_id) REFERENCES `permissions` (`id`);
ALTER TABLE `permissionroles` ADD FOREIGN KEY (role_id) REFERENCES `roles` (`id`);
ALTER TABLE `userroles` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `userroles` ADD FOREIGN KEY (role_id) REFERENCES `roles` (`id`);
ALTER TABLE `auditfields` ADD FOREIGN KEY (audit_id) REFERENCES `audits` (`id`);
ALTER TABLE `authtokens` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `logins` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `emailchanges` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `emailchanges` ADD FOREIGN KEY (authtoken_id) REFERENCES `authtokens` (`id`);
ALTER TABLE `navlinks` ADD FOREIGN KEY (navbar_id) REFERENCES `navbars` (`id`);
ALTER TABLE `navlinks` ADD FOREIGN KEY (parent_id) REFERENCES `navlinks` (`id`);
ALTER TABLE `contents` ADD FOREIGN KEY (page_id) REFERENCES `pages` (`id`);
ALTER TABLE `contents` ADD FOREIGN KEY (parent_id) REFERENCES `contents` (`id`);

-- ---
-- Default values
-- ---

--Login with Username: admin@admin.com, Password: Admin
INSERT INTO `users` VALUES (1,'Admin','Istrator','1990-01-01','admin@admin.com','$2y$10$E4YQjXGmC2ak.8BkKuJjj.fiywpzPvH49qPUqZwHs6Ry2KNm9oDri');

INSERT INTO `roles` VALUES 
(1,'Root Admin','Administrator',0),
(2,'Member','Member',96),
(3,'Verified Email','Verified email',97),
(4,'Unverified Email','Unverified Email',98),
(5,'Guest','Guest',99),
(6,'Deactivated','Deactivated account',100);

INSERT INTO `permissions` VALUES 
(1,'frontend','index','index'),
(2,'frontend','session','login'),
(3,'frontend','account','register'),
(4,'frontend','account','resetpass'),
(5,'frontend','account','verifyemail'),
(6,'frontend','account','changepass'),
(7,'frontend','account','changeemail'),
(8,'frontend','account','revokeemailchange'),
(9,'frontend','account','changeinfo'),
(10,'frontend','account','index'),
(11,'frontend','error','index'),
(12,'frontend','error','notFound'),
(13,'frontend','pagecontents','view'),
(14,'frontend','session','logout'),
(15,'frontend','terms','index'),
(16,'frontend','privacy','index'),
(17,'frontend','credits','index'),
(18,'backend','error','index'),
(19,'backend','error','notFound'),
(20,'backend','index','index'),
(21,'backend','permissions','index'),
(22,'backend','permissions','set'),
(23,'backend','permissions','updatepermissions'),
(24,'backend','users','index'),
(25,'backend','users','edit'),
(26,'backend','users','roles'),
(27,'backend','users','newrole'),
(28,'backend','users','editrole'),
(29,'backend','navbars','index'),
(30,'backend','navbars','new'),
(31,'backend','navbars','edit'),
(32,'backend','navbars','manage'),
(33,'backend','pagecontents','view'),
(34,'backend','pages','index'),
(35,'backend','pages','new'),
(36,'backend','pages','edit'),
(37,'backend','pages','manage'),
(38,'backend','pages','reorder'),
(39,'backend','pages','move'),
(40,'backend','pages','content'),
(41,'backend','pages','deletecontent');

INSERT INTO `permissionroles` VALUES 
(1,1,5),
(2,2,5),
(3,3,5),
(4,4,5),
(5,5,5),
(6,8,5),
(7,11,5),
(8,12,5),
(9,13,5),
(10,15,5),
(11,16,5),
(12,17,5),
(13,6,2),
(14,7,2),
(15,9,2),
(16,10,2);

INSERT INTO `navbars` VALUES 
(1,'Footer'),
(2,'Frontend'),
(3,'Backend'),
(4,'Frontend_header_guest'),
(5,'Frontend_header_member'),
(6,'Frontend_header_admin'),
(7,'Backend_header');

INSERT INTO `navlinks` VALUES 
(1,3,0,'Manage','#',NULL),
(2,3,1,'Users','admin/users',1),
(3,3,2,'Roles','admin/users/roles',2),
(4,3,2,'New Role','admin/users/newrole',2),
(5,3,1,'Permissions','admin/permissions',1),
(6,3,2,'Refresh','admin/permissions/updatepermissions',5),
(7,3,1,'Navbars','admin/navbars',1),
(8,3,2,'New','admin/navbars/new',7),
(9,1,0,'Site','#',NULL),
(10,1,1,'Terms','terms',9),
(11,1,1,'Privacy','privacy',9),
(12,1,1,'Credits','credits',9),
(13,2,0,'Nav','#',NULL),
(14,2,1,'Drop','#',13),
(15,2,1,'Down','#',13),
(16,4,0,'Login','login',NULL),
(17,4,0,'Register','register',NULL),
(18,5,0,'Account','account',NULL),
(19,5,0,'logout','logout',NULL),
(20,6,0,'Admin','admin',NULL),
(21,6,0,'Account','account',NULL),
(22,6,0,'Logout','logout',NULL),
(23,7,0,'Back','index',NULL),
(24,7,0,'Logout','logout',NULL);


INSERT INTO `pages` VALUES 
(1,'Terms Page','Terms of Service',1,'terms'),
(2,'Privacy Page','Privacy Policy',1,'privacy'),
(3,'Credits Page','Credits',1,'credits');

INSERT INTO `contents` VALUES 
(61,NULL,12,0,'<h3>Terms of Service</h3>\n<hr>\n\n<p>Please read these terms of use carefully before you start to use the site.\nBy using our site, you indicate that you accept these terms of use and that\nyou agree to abide by them. If you do not agree to these terms of use, please\nrefrain from using our site.</p>\n\n<p><br></p>\n\n<h5>Reliance On Information Posted & Disclaimer</h5>\n\n<p>The materials contained on our site are provided for general information\npurposes only and do not claim to be or constitute legal or other\nprofessional advice and shall not be relied upon as such.</p>\n\n<p>We do not accept any responsibility for any loss which may arise from\naccessing or reliance on the information on this site and to the fullest\nextent permitted by English law, we exclude all liability for loss or damages\ndirect or indirect arising from use of this site.</p>\n\n<p><br></p>\n\n<h5>Information about us</h5>\n\n<p>CodingBeard.com is a site operated by CodingBeard (\"We\");</p>\n\n<p><br></p>\n\n<h5>Accessing our site</h5>\n\n<p>Access to our site is permitted on a temporary basis, and we reserve the\nright to withdraw or amend the service we provide on our site without notice\n(see below). We will not be liable if for any reason our site is unavailable\nat any time or for any period.</p>\n\n<p><br></p>\n\n<h5>Registering on our site</h5>\n\n<div>\nYou must be over the age of 13 to register on this site.\n</div>\n\n<p><br></p>\n\n<h5>Intellectual property rights</h5>\n\n<p>We are the owner or the licensee of all intellectual property rights in\nour site, and in the material published on it. Those works are protected by\ncopyright laws and treaties around the world. All such rights are\nreserved.</p>\n\n<p>You may print off one copy, and may download extracts, of any page(s) from\nour site for your personal reference and you may draw the attention of others\nwithin your organisation to material posted on our site.</p>\n\n<p>You must not modify the paper or digital copies of any materials you have\nprinted off or downloaded in any way, and you must not use any illustrations,\nphotographs, video or audio sequences or any graphics separately from any\naccompanying text.</p>\n\n<p>Our status (and that of any identified contributors) as the authors of\nmaterial on our site must always be acknowledged.</p>\n\n<p>You must not use any part of the materials on our site for commercial\npurposes without obtaining a licence to do so from us or our licensors.</p>\n\n<p>If you print off, copy or download any part of our site in breach of these\nterms of use, your right to use our site will cease immediately and you must,\nat our option, return or destroy any copies of the materials you have\nmade.</p>\n\n<p><br></p>\n\n<h5>Our site changes regularly</h5>\n\n<p>We aim to update our site regularly, and may change the content at any\ntime. If the need arises, we may suspend access to our site, or close it\nindefinitely. Any of the material on our site may be out of date at any given\ntime, and we are under no obligation to update such material.</p>\n\n<p><br></p>\n\n<h5>Our liability</h5>\n\n<p>The material displayed on our site is provided without any guarantees,\nconditions or warranties as to its accuracy. To the extent permitted by law,\nwe, and third parties connected to us hereby expressly exclude:</p>\n\n<p>All conditions, warranties and other terms which might otherwise be\nimplied by statute, common law or the law of equity.</p>\n\n<p>Any liability for any direct, indirect or consequential loss or damage\nincurred by any user in connection with our site or in connection with the\nuse, inability to use, or results of the use of our site, any websites linked\nto it and any materials posted on it, including, without limitation any\nliability for:</p>\n\n<p></p>\n\n<ul>\n<li>loss of income or revenue</li>\n\n<li>loss of business</li>\n\n<li>loss of profits or contracts</li>\n\n<li>loss of anticipated savings</li>\n\n<li>loss of data</li>\n\n<li>loss of goodwill</li>\n\n<li>wasted management or office time</li>\n</ul>\n\n<p></p>\n\n<p>Any other loss or damage of any kind, however arising and whether caused\nby tort (including negligence), breach of contract or otherwise, even if\nforeseeable, provided that this condition shall not prevent claims for loss\nof or damage to your tangible property or any other claims for direct\nfinancial loss that are not excluded by any of the categories set out\nabove.</p>\n\n<p>This does not affect our liability for death or personal injury arising\nfrom our negligence, nor our liability for fraudulent misrepresentation or\nmisrepresentation as to a fundamental matter, nor any other liability which\ncannot be excluded or limited under applicable law.</p>\n\n<p><br></p>\n\n<h5>Information about you and your visits to our site</h5>\n\n<p>We process information about you in accordance with our \n<a href=\"{{ url(\'privacy\') }}\" target=\"_blank\">privacy policy</a>. By using our\nsite, you consent to such processing and you warrant that all data provided\nby you is accurate.</p>\n\n<p><br></p>\n\n<h5>Viruses, hacking and other offences</h5>\n\n<p>You must not misuse our site by knowingly introducing viruses, trojans,\nworms, logic bombs or other material which is malicious or technologically\nharmful. You must not attempt to gain unauthorised access to our site, the\nserver on which our site is stored or any server, computer or database\nconnected to our site. You must not attack our site via a denial-of-service\nattack or a distributed denial-of service attack.</p>\n\n<p>By breaching this provision, you would commit a criminal offence under the\nComputer Misuse Act 1990. We will report any such breach to the relevant law\nenforcement authorities and we will co-operate with those authorities by\ndisclosing your identity to them. In the event of such a breach, your right\nto use our site will cease immediately.</p>\n\n<p>We will not be liable for any loss or damage caused by a distributed\ndenial-of-service attack, viruses or other technologically harmful material\nthat may infect your computer equipment, computer programs, data or other\nproprietary material due to your use of our site or to your downloading of\nany material posted on it, or on any website linked to it.</p>\n\n<p><br></p>\n\n<h5>Links from our site</h5>\n\n<p>Where our site contains links to other sites and resources provided by\nthird parties, these links are provided for your information only. We have no\ncontrol over the contents of those sites or resources, and accept no\nresponsibility for them or for any loss or damage that may arise from your\nuse of them. When accessing a site via our website we advise you check their\nterms of use and privacy policies to ensure compliance and determine how they\nmay use your information.</p>\n\n<p><br></p>\n\n<h5>Jurisdiction and applicable law</h5>\n\n<p>The English courts will have non-exclusive jurisdiction over any claim\narising from, or related to, a visit to our site.</p>\n\n<p>These terms of use and any dispute or claim arising out of or in\nconnection with them or their subject matter or formation (including\nnon-contractual disputes or claims) shall be governed by and construed in\naccordance with the law of England and Wales.</p>\n\n<p><br></p>\n\n<h5>Variations</h5>\n\n<p>We may revise these terms of use at any time by amending this page. You\nare expected to check this page from time to time to take notice of any\nchanges we made, as they are binding on you. Some of the provisions contained\nin these terms of use may also be superseded by provisions or notices\npublished elsewhere on our site.</p>\n\n<p><br></p>\n\n<h5>Your concerns</h5>\n\n<p>If you have any concerns about material which appears on our site, please\ncontact us at: webmaster@codingbeard.com</p>\n\n<p><br></p>\n\n<p>Thank you for visiting our site.</p>\n<p>This document was last updated on Feburary 26, 2015</p>',1,NULL),
(62,NULL,12,0,'<h3>Privacy Policy</h3>\n<hr />\n<p>This Privacy Policy governs the manner in which CodingBard collects, uses,\nmaintains and discloses information collected from users (each, a \"User\") of\nthe CodingBeard.com website (\"Site\"). This privacy policy applies to\nthe Site and all products and services offered by CodingBard.</p>\n\n<p><br></p>\n\n<h5>Personal identification information</h5>\n\n<p>We may collect personal identification information from Users in a variety\nof ways, including, but not limited to, when Users visit our site, register on\nthe site, place an order, subscribe to the newsletter, fill out a form, and in\nconnection with other activities, services, features or resources we make\navailable on our Site. Users may be asked for, as appropriate, name, email\naddress. Users may, however, visit our Site anonymously. We will collect\npersonal identification information from Users only if they voluntarily submit\nsuch information to us. Users can always refuse to supply personally\nidentification information, except that it may prevent them from engaging in\ncertain Site related activities.</p>\n\n<p><br></p>\n\n<h5>Non-personal identification information</h5>\n\n<p>We collect non-personal identification information about Users whenever they\ninteract with our Site via Google Analytics. Non-personal identification\ninformation may include the browser name, the type of computer and technical\ninformation about Users means of connection to our Site, such as the operating\nsystem and the Internet service providers utilized and other similar\ninformation.<br>\nIt is possible to disable this through various methods which may be found\n<a href=\"http://www.google.co.uk/search?q=disable+google+analytics+tracking\"\n   target=\"_blank\">Here</a></p>\n\n<p><br></p>\n\n<h5>Web browser cookies</h5>\n\n<p>Our Site may use \"cookies\" to enhance User experience. User\'s web browser\nplaces cookies on their hard drive for record-keeping purposes and sometimes to\ntrack information about them. User may choose to set their web browser to\nrefuse cookies, or to alert you when cookies are being sent. If they do so,\nnote that some parts of the Site may not function properly.</p>\n\n<p><br></p>\n\n<h5>How we use collected information</h5>\n\n<p>CodingBard may collect and use Users personal information for the following\npurposes:</p>\n\n<ul>\n<li>\n  <p>We may use feedback you provide to improve our products and\n	services.</p>\n</li>\n\n<li>\n  <p><span></span>We may use the information Users provide about themselves\n	when placing an order only to provide service to that order. We do not\n	share this information with outside parties except to the extent necessary\n	to provide the service.<br></p>\n</li>\n\n<li>\n  <p>To send Users information they agreed to receive about topics we think\n	will be of interest to them.<br></p>\n</li>\n\n<li>\n  <p>We may use the email address to send User information and updates\n	pertaining to their order. It may also be used to respond to their\n	inquiries, questions, and/or other requests. If User decides to opt-in to\n	our mailing list, they will receive emails that may include company news,\n	updates, related product or service information, etc. If at any time the\n	User would like to unsubscribe from receiving future emails, we include\n	detailed unsubscribe instructions at the bottom of each email.</p>\n</li>\n</ul>\n\n<p><br></p>\n\n<h5>How we protect your information</h5>\n\n<p>We adopt appropriate data collection, storage and processing practices and\nsecurity measures to protect against unauthorized access, alteration,\ndisclosure or destruction of your personal information, username, password,\ntransaction information and data stored on our Site.</p>\n\n<p>Sensitive and private data exchange between the Site and its Users happens\nover a SSL secured communication channel and is encrypted and protected with\ndigital signatures.</p>\n\n<p><br></p>\n\n<h5>Sharing your personal information</h5>\n\n<p>We do not sell, trade, or rent Users personal identification information to\nothers. We may share generic aggregated demographic information not linked to\nany personal identification information regarding visitors and users with our\nbusiness partners, trusted affiliates and advertisers for the purposes outlined\nabove.</p>\n\n<p><br></p>\n\n<h5>Third party websites</h5>\n\n<p>Users may find advertising or other content on our Site that link to the\nsites and services of our partners, suppliers, advertisers, sponsors, licensors\nand other third parties. We do not control the content or links that appear on\nthese sites and are not responsible for the practices employed by websites\nlinked to or from our Site. In addition, these sites or services, including\ntheir content and links, may be constantly changing. These sites and services\nmay have their own privacy policies and customer service policies. Browsing and\ninteraction on any other website, including websites which have a link to our\nSite, is subject to that website\'s own terms and policies.</p>\n\n<p><br></p>\n\n<h5>Compliance with children\'s online privacy protection act</h5>\n\n<p>Protecting the privacy of the very young is especially important. For that\nreason, we never collect or maintain information at our Site from those we\nactually know are under 13, and no part of our website is structured to attract\nanyone under 13.</p>\n\n<p><br></p>\n\n<h5>Changes to this privacy policy</h5>\n\n<p>CodingBard has the discretion to update this privacy policy at any time.\nWhen we do, we will revise the updated date at the bottom of this page. We\nencourage Users to frequently check this page for any changes to stay informed\nabout how we are helping to protect the personal information we collect. You\nacknowledge and agree that it is your responsibility to review this privacy\npolicy periodically and become aware of modifications.</p>\n\n<p><br></p>\n\n<h5>Your acceptance of these terms</h5>\n\n<p>By using this Site, you signify your acceptance of this policy and \n<a href=\"{{ url(\'terms\') }}\" target=\"_blank\">terms of service</a>. \nIf you do not agree to this policy, please do not use our Site. Your continued use\nof the Site following the posting of changes to this policy will be deemed your\nacceptance of those changes.</p>\n\n<p><br></p>\n\n<h5>Contacting us</h5>\n\n<p>If you have any questions about this Privacy Policy, the practices of this\nsite, or your dealings with this site, please contact us at: webmaster@codingbeard.com</p>\n\n<p><br></p>\n\n<p>This document was last updated on Feburary 26, 2015</p>',2,NULL),
(64,NULL,12,0,'{% set builtwith = [\r\n[\r\n	\'Digital Ocean\', \r\n	\'https://www.digitalocean.com\', \r\n	\'/img/credits/digitalocean.png\'\r\n],\r\n[\r\n	\'Ubuntu\', \r\n	\'http://www.ubuntu.com\', \r\n	\'/img/credits/ubuntu.png\'\r\n],\r\n[\r\n	\'PHP\', \r\n	\'https://php.net\', \r\n	\'/img/credits/php.png\'\r\n],\r\n[\r\n	\'Phalcon\', \r\n	\'http://phalconphp.com/\', \r\n	\'/img/credits/phalcon.png\'\r\n],\r\n[\r\n	\'Nginx\', \r\n	\'http://nginx.org\', \r\n	\'/img/credits/nginx.png\'\r\n],\r\n[\r\n	\'Mysql\', \r\n	\'http://www.mysql.com\', \r\n	\'/img/credits/mysql.png\'\r\n],\r\n[\r\n	\'Netbeans IDE\', \r\n	\'https://netbeans.org\', \r\n	\'/img/credits/netbeans.gif\'\r\n]\r\n	]\r\n%}\r\n{% set plugins = [\r\n        [\'jQuery\', \'Javascript\', \'http://jquery.com\'],\r\n        [\'jQuery UI\', \'Javascript\', \'http://jqueryui.com\'],\r\n        [\'Dropzone\', \'Javascript\', \'http://www.dropzonejs.com\'],\r\n        [\'Tag it\', \'Javascript\', \'https://github.com/aehlke/tag-it\'],\r\n        [\'DataTables\', \'Javascript\', \'http://www.datatables.net\'],\r\n        [\'Cookiebanner\', \'Javascript\', \'https://github.com/dobarkod/cookie-banner\'],\r\n        [\'Summernote\', \'Javascript\', \'https://github.com/HackerWins/summernote\'],\r\n        [\'Simple Image\', \'PHP\', \'https://github.com/claviska/SimpleImage\'],\r\n        [\'Phalcon Mandrill\', \'PHP\', \'https://gitlab.com/tartan/phalconphp-mandrill-component\'],\r\n        [\'Materialize\', \'CSS\', \'http://materializecss.com\'],\r\n        [\'Font Awesome\', \'CSS\', \'http://fortawesome.github.io/Font-Awesome/\']\r\n	]	\r\n%}\r\n<div id=\"flash-container\">\r\n        {{ flashSession.output() }}\r\n</div>\r\n<h3>Built with</h3>\r\n<hr />\r\n<div class=\"row\">\r\n    {% for credit in builtwith %}\r\n	    <div class=\"col l3 m4 s6\" style=\"padding-bottom: 10px; height: 200px;\">\r\n            <h5><a href=\"{{ credit[1] }}\">{{ credit[0] }}</a></h5>\r\n            <a href=\"{{ credit[1] }}\">\r\n                <img style=\"max-height: 100px;\" src=\"{{ credit[2] }}\" alt=\"{{ credit[0] }}\" />\r\n            </a>\r\n	    </div>\r\n    {% endfor %}\r\n</div>\r\n<div class=\"row\">\r\n    <div class=\"col s12\">\r\n        <table class=\"table bordered striped condensed\">\r\n	        <thead>\r\n                <tr>\r\n                    <td>Name</td>\r\n                    <td>Type</td>\r\n                    <td>Link</td>\r\n                </tr>\r\n	        </thead>\r\n	        <tbody>\r\n                {% for credit in plugins %}\r\n                    <tr>\r\n	                    <td>{{ credit[0] }}</td>\r\n	                    <td>{{ credit[1] }}</td>\r\n	                    <td><a href=\"{{ credit[2] }}\">{{ credit[0] }}</a></td>\r\n                    </tr>\r\n                {% endfor %}\r\n	        </tbody>\r\n        </table>\r\n    </div>\r\n</div>',3,NULL);