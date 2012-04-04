CREATE TABLE IF NOT EXISTS zt_simple_pages (
  simple_page_id int(11) NOT NULL auto_increment COMMENT 'Identifikator jednostavne stranice.',
  simple_page_alias varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Alias stranice',
  simple_page_name varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv jednostavne stranice.',
  simple_page_body text collate utf8_unicode_ci COMMENT 'Sadrzaj jednostavne stranice.',
  simple_page_creation_datetime datetime NOT NULL COMMENT 'Datum stvaranja jednostavne stranice',
  simple_page_modification_datetime datetime default NULL COMMENT 'Datum modifikacije jednostavne stranice.',
  PRIMARY KEY  (simple_page_id),
  UNIQUE KEY simple_page_alias (simple_page_alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
::
CREATE TABLE IF NOT EXISTS zt_simple_page_page (
  simple_page_page_id int(11) NOT NULL auto_increment COMMENT 'Identifikator',
  simple_page_id int(11) NOT NULL COMMENT 'Identifikator jednostavne stranice',
  page_id int(11) NOT NULL COMMENT 'Identifikator stranice',
  PRIMARY KEY  (simple_page_page_id),
  KEY simple_page_id (simple_page_id,page_id),
  KEY page_id (page_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
::
ALTER TABLE zt_simple_page_page
  ADD CONSTRAINT zt_simple_page_page_ibfk_1 FOREIGN KEY (simple_page_id) REFERENCES zt_simple_pages (simple_page_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT zt_simple_page_page_ibfk_2 FOREIGN KEY (page_id) REFERENCES zt_pages (page_id) ON DELETE CASCADE ON UPDATE CASCADE;