#    Birrols
#    Web app for craft beer lovers.
#    Copyright (C) 2012 ßingen Eguzkitza <bingentxu@gmail.com>
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# --------------------------------------------------------

SET character_set_client = utf8;


CREATE TABLE IF NOT EXISTS business (
  auto_id int(11) NOT NULL auto_increment,
  brewery boolean NOT NULL default FALSE,
  pub boolean NOT NULL default FALSE,
  store boolean NOT NULL default FALSE,
  homebrew_store boolean NOT NULL default FALSE,
  food boolean NOT NULL default FALSE,
  wifi boolean NOT NULL default FALSE,
  name char(60) collate utf8_spanish_ci NOT NULL,
  avatar int(10) unsigned NOT NULL default '0',
  description text collate utf8_spanish_ci NOT NULL,
  taps int(3) default 0,
  score decimal(3,2) default 0,
  address_1 char(128) collate utf8_spanish_ci default NULL,
  address_2 char(128) collate utf8_spanish_ci default NULL,
  zip_code char(10) collate utf8_spanish_ci default NULL,
  country_id int(11) default NULL,
  state char(50) collate utf8_spanish_ci default NULL,
  city char(50) collate utf8_spanish_ci default NULL,
  url char(128) collate utf8_spanish_ci NOT NULL,
  facebook_url char(128) collate utf8_spanish_ci NOT NULL,
  twitter_url char(128) collate utf8_spanish_ci NOT NULL,
  gplus_url char(128) collate utf8_spanish_ci NOT NULL,
  4square_url char(128) collate utf8_spanish_ci NOT NULL,
  email char(64) collate utf8_spanish_ci NOT NULL,
  phone char(16) collate utf8_spanish_ci default NULL,
  lat char(10) collate utf8_spanish_ci default NULL,
  lon char(10) collate utf8_spanish_ci default NULL,
  user_admin_id int(11), 
  register_id int(11), 
  PRIMARY KEY  (auto_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Table structure for table business_avatars
--

DROP TABLE IF EXISTS business_avatars;
CREATE TABLE IF NOT EXISTS `business_avatars` (
  avatar_id int(11) NOT NULL,
  avatar_modified timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  avatar_image blob NOT NULL,
  PRIMARY KEY  (avatar_id)
) DEFAULT CHARSET=utf8;

--
-- View business_view
--
DROP VIEW IF EXISTS business_view;
CREATE VIEW business_view AS (SELECT b.*, c.name country
FROM business b, countries c
WHERE b.country_id = c.auto_id
);

# --------------------------------------------------------

CREATE TABLE IF NOT EXISTS beer_categories (
  auto_id int(11) NOT NULL auto_increment,
  category enum('Ale', 'Lager', 'Lambic') character set utf8 NOT NULL,
  PRIMARY KEY  (auto_id),
  UNIQUE KEY category (category)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

TRUNCATE TABLE beer_categories;

LOCK TABLES beer_categories WRITE;
INSERT INTO beer_categories VALUES (1,'Ale'),(2,'Lager'), (3,'Lambic');
UNLOCK TABLES;

CREATE TABLE IF NOT EXISTS beer_types (
  auto_id int(11) NOT NULL auto_increment,
  type char(32) collate utf8_spanish_ci NOT NULL,
  category_id int(11),
  PRIMARY KEY  (auto_id),
  UNIQUE KEY type (type)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS beers (
  auto_id int(11) NOT NULL auto_increment,
  name char(128) collate utf8_spanish_ci NOT NULL,
  brewery_id int(11),
  category_id int(11),
  type_id int(11),
  abv decimal(4,2) default NULL,
  ibu int(10) default NULL,
  og int(4) default NULL,
  srm int(3) default NULL,
  ebc int(3) default NULL,
  malts char(128) collate utf8_spanish_ci default NULL,
  hops char(128) collate utf8_spanish_ci default NULL,
  description text collate utf8_spanish_ci default NULL,
  avatar int(10) unsigned NOT NULL default '0',
  score decimal(3,2) default 0,
  register_id int(11), 
  PRIMARY KEY  (auto_id),
  UNIQUE KEY brewery_name (brewery_id,name)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP VIEW IF EXISTS beers_view;
CREATE VIEW beers_view AS (
  SELECT b.*, w.name brewery, w.user_admin_id, c.category, t.type, p.name country, p.auto_id country_id
  FROM beers b 
  INNER JOIN business w ON b.brewery_id = w.auto_id
  LEFT JOIN beer_categories c ON b.category_id = c.auto_id
  LEFT JOIN beer_types t ON  b.type_id = t.auto_id
  LEFT JOIN countries p ON w.country_id = p.auto_id
  WHERE w.brewery 
);

--
-- Table structure for table beers_avatars
--

DROP TABLE IF EXISTS beers_avatars;
CREATE TABLE IF NOT EXISTS `beers_avatars` (
  avatar_id int(11) NOT NULL,
  avatar_modified timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  avatar_image blob NOT NULL,
  PRIMARY KEY  (avatar_id)
) DEFAULT CHARSET=utf8;

# --------------------------------------------------------

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  auto_id int(11) NOT NULL auto_increment,
  username char(32) collate utf8_spanish_ci NOT NULL,
  password char(64) collate utf8_spanish_ci NOT NULL,
  email char(64) collate utf8_spanish_ci NOT NULL,
  type enum('disabled','lover','business','admin') character set utf8 NOT NULL default 'lover',
  avatar int(10) unsigned NOT NULL default '0',
  modified timestamp NOT NULL default CURRENT_TIMESTAMP,
  date timestamp NOT NULL default '0000-00-00 00:00:00',
  validated_date timestamp NULL default NULL,
  ip char(32) collate utf8_spanish_ci default NULL,
  name char(60) collate utf8_spanish_ci NOT NULL,
  last_name char(60) collate utf8_spanish_ci NOT NULL,
  language_id tinyint(2) unsigned NOT NULL default '1',
  url char(128) collate utf8_spanish_ci,
  sex enum('M','F') character set utf8,
  country char(50) collate utf8_spanish_ci default NULL,
  birthday date NOT NULL default '1900-00-00',
  username_register char(32) collate utf8_spanish_ci default NULL,
  email_register char(64) collate utf8_spanish_ci default NULL,
  google_id char(32) default NULL,
  PRIMARY KEY auto_id (auto_id),
  UNIQUE KEY username (username)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- View structure for view lovers
--

-- DROP VIEW IF EXISTS lovers;
-- CREATE VIEW lovers AS (SELECT *
-- FROM users
-- WHERE type = 'lover');

--
-- Table structure for table taps
--

DROP TABLE IF EXISTS taps;
CREATE TABLE IF NOT EXISTS taps (
  auto_id int(11) NOT NULL,
  business_id int(11) NOT NULL,
  tap_id int(3) NOT NULL,
  beer_id int(11) NOT NULL,
  actual boolean default FALSE,
  register_id int(11), 
  modified timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (auto_id)
) DEFAULT CHARSET=utf8;

DROP VIEW IF EXISTS taps_view;
CREATE VIEW taps_view AS (SELECT t.*, w.name brewery, b.name beer, u.username user
FROM taps t, beers b, business w, users u
WHERE t.beer_id = b.auto_id AND b.brewery_id = w.auto_id AND t.register_id = u.auto_id
);

--
-- Table structure for table languages
--

DROP TABLE IF EXISTS languages;
CREATE TABLE IF NOT EXISTS languages (
  auto_id int(11) NOT NULL auto_increment,
  language char(32) NOT NULL,
  locale char(10) default NULL,
PRIMARY KEY auto_id (auto_id),
UNIQUE KEY language (language)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

LOCK TABLES languages WRITE;
INSERT INTO languages VALUES (1,'Castellano', 'es'),(2,'Català', 'ca'), (3,'English', 'en');
UNLOCK TABLES;

--
-- Table structure for table users_avatars
--

DROP TABLE IF EXISTS users_avatars;
CREATE TABLE IF NOT EXISTS `users_avatars` (
  avatar_id int(11) NOT NULL,
  avatar_modified timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  avatar_image blob NOT NULL,
  PRIMARY KEY  (avatar_id)
) DEFAULT CHARSET=utf8;

--
-- Table structure for table logs
--

DROP TABLE IF EXISTS logs;
CREATE TABLE IF NOT EXISTS logs (
  log_id int(11) NOT NULL auto_increment,
  log_date timestamp NOT NULL default CURRENT_TIMESTAMP,
  log_type char(32) NOT NULL,
  log_ref_id int(11) unsigned NOT NULL,
  log_user_id int(11) NOT NULL,
  log_ip char(24) character set utf8 collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (log_id),
  KEY log_date (log_date),
  KEY log_type (log_type,log_ref_id),
  KEY log_type_2 (log_type,log_date)
) DEFAULT CHARSET=utf8;

# --------------------------------------------------------
DROP TABLE IF EXISTS countries;
CREATE TABLE IF NOT EXISTS countries (
  auto_id int(11) NOT NULL auto_increment,
  language_id tinyint(2) unsigned NOT NULL default '1',
  name char(60) collate utf8_spanish_ci NOT NULL,
  alternative_spellings char(128) collate utf8_spanish_ci,
  relevancy decimal(2,1) default 1,
  PRIMARY KEY  (auto_id),
  UNIQUE KEY name (language_id,name)
) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

# --------------------------------------------------------

-- CREATE TABLE IF NOT EXISTS  (
--   auto_id int(11) NOT NULL auto_increment,
--   x char(32) collate utf8_spanish_ci NOT NULL,
--   PRIMARY KEY  (auto_id),
--   UNIQUE KEY x (x)
-- ) DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
