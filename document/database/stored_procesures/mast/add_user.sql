DELIMITER $$

DROP PROCEDURE IF EXISTS `add_user`$$

CREATE PROCEDURE `add_user`(IN i_userid VARCHAR(32), IN i_name VARCHAR(32), IN i_email VARCHAR(128), IN i_pwd_hash VARCHAR(256), IN i_birthday INT, IN i_gender CHAR(1), IN i_residence_hash VARCHAR(256), IN i_phone VARCHAR(32), IN i_info VARCHAR(4096))
label_procedure: BEGIN
  DECLARE v_userid BIGINT;
  DECLARE v_duplicated BOOL DEFAULT TRUE;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_duplicated = FALSE;

  SELECT userid FROM user_mast WHERE email = i_email OR residence_hash = i_residence_hash INTO v_userid;

  IF v_duplicated THEN
    -- Duplicated email or residence number
    SELECT -1 AS errno, 'Deplicated user' AS errstr;
    LEAVE label_procedure;
  END IF;

  INSERT INTO user_mast (userid, `name`, email, pwd_hash, birthday, gender, residence_hash, phone, info, last_login)
    VALUES (i_userid, i_name, i_email, i_pwd_hash, i_birthday, i_gender, i_residence_hash, i_phone, i_info, CURRENT_TIMESTAMP);

  SELECT 0 AS errno, 'Ok' AS errstr;
  END$$

DELIMITER ;
