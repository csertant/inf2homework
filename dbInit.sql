-- Schema scalp
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `scalp` ;
CREATE SCHEMA IF NOT EXISTS `scalp` DEFAULT CHARACTER SET utf8 ;
USE `scalp` ;

-- Table `scalp`.`People`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `scalp`.`People` ;

CREATE TABLE IF NOT EXISTS `scalp`.`People` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` NVARCHAR(30) NOT NULL,
  `address` NVARCHAR(100) NOT NULL,
  `email` VARCHAR(30) NOT NULL,
  `cellphone` VARCHAR(12) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- Table `scalp`.`Company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `scalp`.`Company` ;

CREATE TABLE IF NOT EXISTS `scalp`.`Company` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` NVARCHAR(50) NOT NULL,
  `address` NVARCHAR(100) NOT NULL,
  `category` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- Table `scalp`.`Project`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `scalp`.`Project` ;

CREATE TABLE IF NOT EXISTS `scalp`.`Project` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` NVARCHAR(45) NOT NULL,
  `description` NVARCHAR(1000) NULL,
  `startDate` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- Table `scalp`.`CompaniesProjects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `scalp`.`CompaniesProjects` ;

CREATE TABLE IF NOT EXISTS `scalp`.`CompaniesProjects` (
  `Company_id` INT NOT NULL,
  `Project_id` INT NOT NULL,
  PRIMARY KEY (`Company_id`, `Project_id`),
  INDEX `fk_Company_has_Project_Project1_idx` (`Project_id` ASC) VISIBLE,
  INDEX `fk_Company_has_Project_Company1_idx` (`Company_id` ASC) VISIBLE,
  CONSTRAINT `fk_Company_has_Project_Company1`
    FOREIGN KEY (`Company_id`)
    REFERENCES `scalp`.`Company` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Company_has_Project_Project1`
    FOREIGN KEY (`Project_id`)
    REFERENCES `scalp`.`Project` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- Table `scalp`.`ProjectsContributors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `scalp`.`ProjectsContributors` ;

CREATE TABLE IF NOT EXISTS `scalp`.`ProjectsContributors` (
  `Project_id` INT NOT NULL,
  `People_id` INT NOT NULL,
  PRIMARY KEY (`Project_id`, `People_id`),
  INDEX `fk_Project_has_People_People1_idx` (`People_id` ASC) VISIBLE,
  INDEX `fk_Project_has_People_Project1_idx` (`Project_id` ASC) VISIBLE,
  CONSTRAINT `fk_Project_has_People_Project1`
    FOREIGN KEY (`Project_id`)
    REFERENCES `scalp`.`Project` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Project_has_People_People1`
    FOREIGN KEY (`People_id`)
    REFERENCES `scalp`.`People` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;