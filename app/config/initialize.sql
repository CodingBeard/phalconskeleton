



-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

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
  `active` TINYINT(4) NULL DEFAULT NULL,
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



INSERT INTO `roles` (`name`, `description`, `level`) VALUES
('Root Admin', 'Administrator', 0),
('Member', 'Member', 98),
('Verified Email', 'Verified email', 99),
('Unverified Email', 'Unverified Email', 99),
('Guest', 'Guest', 100);