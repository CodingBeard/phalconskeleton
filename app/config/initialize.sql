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
  `link` VARCHAR(255) NULL DEFAULT NULL,
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
(13,'frontend','session','logout'),
(14,'frontend','site','terms'),
(15,'frontend','site','privacy'),
(16,'frontend','site','credits'),
(17,'backend','error','index'),
(18,'backend','error','notFound'),
(19,'backend','index','index'),
(20,'backend','permissions','index'),
(21,'backend','permissions','set'),
(22,'backend','permissions','updatepermissions'),
(23,'backend','users','index'),
(24,'backend','users','edit'),
(25,'backend','users','roles'),
(26,'backend','users','newrole'),
(27,'backend','users','editrole');

INSERT INTO `permissionroles` VALUES 
(1,1,5),
(2,2,5),
(4,4,5),
(6,5,5),
(14,3,5),
(16,17,5),
(17,18,5),
(18,11,5),
(19,12,5),
(20,7,2),
(21,9,2),
(22,6,2),
(23,10,2),
(24,8,5),
(25,13,2),
(26,16,5),
(27,15,5),
(28,14,5);

INSERT INTO `navbars` VALUES 
(1,'Footer'),
(2,'Frontend'),
(3,'Backend'),
(4,'Frontend_header_guest'),
(5,'Frontend_header_member'),
(6,'Frontend_header_admin'),
(7,'Backend_header');

INSERT INTO `navlinks` VALUES 
(1,1,'Site','#',NULL),
(2,1,'Terms','terms',1),
(3,1,'Privacy','privacy',1),
(4,1,'Credits','credits',1),
(5,2,'Nav','#',NULL),
(6,2,'Drop','#',5),
(7,2,'Down','#',5),
(8,3,'Manage','#',NULL),
(9,3,'Users','admin/users',8),
(10,3,'Roles','admin/users/roles',9),
(11,3,'New Role','admin/users/newrole',9),
(12,3,'Permissions','admin/permissions',8),
(13,3,'Refresh','admin/permissions/updatepermissions',12),
(14,3,'Navbars','admin/navbars',8),
(15,3,'New','admin/navbars/new',14),
(16,4,'Login','login',NULL),
(17,4,'Register','register',NULL),
(18,5,'Account','account',NULL),
(19,5,'logout','logout',NULL),
(20,6,'Admin','admin',NULL),
(21,6,'Account','account',NULL),
(22,6,'Logout','logout',NULL),
(23,7,'Back','index',NULL),
(24,7,'Logout','logout',NULL);