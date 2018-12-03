DELIMITER $$

DROP FUNCTION IF EXISTS `json_encode`$$

CREATE
  FUNCTION `json_encode`(i_str TEXT)
  RETURNS TEXT
  BEGIN
    RETURN REPLACE(REPLACE(i_str, '\\', '\\\\'), '"', '\"');
  END$$

DELIMITER ;
