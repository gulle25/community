DELIMITER $$

DROP PROCEDURE IF EXISTS `get_user_mast`$$

CREATE PROCEDURE `get_user_mast`(IN i_category VARCHAR(32), IN i_value VARCHAR(256), IN i_login BOOL)
label_procedure: BEGIN
-- i_category : 'email', 'userid', 'name', 'residence', 'phone'
  DECLARE v_userid VARCHAR(32);
  DECLARE v_email VARCHAR(128);
  DECLARE v_name VARCHAR(32);
  DECLARE v_pwd_hash VARCHAR(256);
  DECLARE v_grade VARCHAR(32);
  DECLARE v_birthday INT;
  DECLARE v_gender VARCHAR(1);
  DECLARE v_phone VARCHAR(32);
  DECLARE v_cash BIGINT;
  DECLARE v_point BIGINT;
  DECLARE v_reg_date TIMESTAMP;
  DECLARE v_last_login TIMESTAMP;
  DECLARE v_user_info VARCHAR(4096);
  DECLARE v_admin_info VARCHAR(4096);
  DECLARE v_cafe_info VARCHAR(4096);
  DECLARE v_cafe_type VARCHAR(32);
  DECLARE v_cafe_name VARCHAR(32);
  DECLARE v_cafe_role VARCHAR(512);
  DECLARE v_cafe_last_visit TIMESTAMP;
  DECLARE v_cafe_bookmark TINYINT;
  DECLARE v_cafeid VARCHAR(32);
  DECLARE v_count INT;

  DECLARE v_not_found BOOL DEFAULT FALSE;
  DECLARE cur_cafe CURSOR FOR SELECT c.cafeid, c.type, c.name, u.role, u.last_visit, u.bookmark FROM cafe_mast c, cafe_user_mast u WHERE c.cafeid = u.cafeid AND u.userid = v_userid;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_not_found = TRUE;

  -- 사용자 기본 정보 조회
  SET v_not_found = FALSE;

  CASE i_category
  WHEN 'userid' THEN
    SELECT u.userid, pwd_hash, email, NAME, IFNULL(grade, 'user'), birthday, gender, phone, cash, `point`, reg_date, last_login, u.info, IFNULL(a.info, '{}')
      FROM user_mast u LEFT JOIN administrator a ON u.userid = a.userid
      WHERE u.userid = i_value
      INTO v_userid, v_pwd_hash, v_email, v_name, v_grade, v_birthday, v_gender, v_phone, v_cash, v_point, v_reg_date, v_last_login, v_user_info, v_admin_info;
  WHEN 'email' THEN
    SELECT u.userid, pwd_hash, email, NAME, IFNULL(grade, 'user'), birthday, gender, phone, cash, `point`, reg_date, last_login, u.info, IFNULL(a.info, '{}')
      FROM user_mast u LEFT JOIN administrator a ON u.userid = a.userid
      WHERE email = i_value
      INTO v_userid, v_pwd_hash, v_email, v_name, v_grade, v_birthday, v_gender, v_phone, v_cash, v_point, v_reg_date, v_last_login, v_user_info, v_admin_info;
  WHEN 'name' THEN
    SELECT u.userid, pwd_hash, email, NAME, IFNULL(grade, 'user'), birthday, gender, phone, cash, `point`, reg_date, last_login, u.info, IFNULL(a.info, '{}')
      FROM user_mast u LEFT JOIN administrator a ON u.userid = a.userid
      WHERE NAME = i_value
      INTO v_userid, v_pwd_hash, v_email, v_name, v_grade, v_birthday, v_gender, v_phone, v_cash, v_point, v_reg_date, v_last_login, v_user_info, v_admin_info;
  WHEN 'residence' THEN
    SELECT u.userid, pwd_hash, email, NAME, IFNULL(grade, 'user'), birthday, gender, phone, cash, `point`, reg_date, last_login, u.info, IFNULL(a.info, '{}')
      FROM user_mast u LEFT JOIN administrator a ON u.userid = a.userid
      WHERE residence_hash = i_value
      INTO v_userid, v_pwd_hash, v_email, v_name, v_grade, v_birthday, v_gender, v_phone, v_cash, v_point, v_reg_date, v_last_login, v_user_info, v_admin_info;
  WHEN 'phone' THEN
    IF LENGTH(i_value) = 0 THEN
      SELECT -2 AS errno, 'Key value is empty' AS errstr;
      LEAVE label_procedure;
    ELSE
      SELECT u.userid, pwd_hash, email, NAME, IFNULL(grade, 'user'), birthday, gender, phone, cash, `point`, reg_date, last_login, u.info, IFNULL(a.info, '{}')
        FROM user_mast u LEFT JOIN administrator a ON u.userid = a.userid
        WHERE phone = i_value
        INTO v_userid, v_pwd_hash, v_email, v_name, v_grade, v_birthday, v_gender, v_phone, v_cash, v_point, v_reg_date, v_last_login, v_user_info, v_admin_info;
    END IF;
  ELSE
    SELECT -1 AS errno, 'User not found' AS errstr;
    LEAVE label_procedure;
  END CASE;

  IF v_not_found THEN
    SELECT -1 AS errno, 'User not found' AS errstr;
    LEAVE label_procedure;
  END IF;

  IF i_login THEN
    -- 로그인 시각 업데이트
    UPDATE user_mast SET last_login = CURRENT_TIMESTAMP WHERE userid = v_userid;
    SET v_last_login = CURRENT_TIMESTAMP;
  END IF;

  -- 가입한 카페 목록
  OPEN cur_cafe;
  SET v_cafe_info = '{';
  SET v_not_found = FALSE;
  SET v_count = 0;

  read_loop: LOOP
    FETCH cur_cafe INTO v_cafeid, v_cafe_type, v_cafe_name, v_cafe_role, v_cafe_last_visit, v_cafe_bookmark;
    IF v_not_found THEN
      LEAVE read_loop;
    END IF;

    IF v_count > 0 THEN
      SET v_cafe_info = CONCAT(v_cafe_info, ',');
    END IF;
    SET v_count = v_count + 1;

    SET v_cafe_info = CONCAT(v_cafe_info, '"', v_cafeid, '":{"type":"', v_cafe_type, '","name":"', json_encode(v_cafe_name),
      '","role":', v_cafe_role, ',"last_visit":"', IFNULL(v_cafe_last_visit,''), '","bookmark":', v_cafe_bookmark, '}');
  END LOOP;

  CLOSE cur_cafe;
  SET v_cafe_info = CONCAT(v_cafe_info, '}');

  -- 결과 반환
  SELECT 0 errno, v_userid userid, v_email email, v_pwd_hash pwd_hash, v_name user_name, v_grade grade, v_birthday birthday, v_gender gender, v_phone phone,
    v_cash cash, v_point point, v_reg_date reg_date, v_last_login last_login, v_user_info user_info_json, v_cafe_info cafe_info_json, v_admin_info admin_info_json;
END$$

DELIMITER ;
