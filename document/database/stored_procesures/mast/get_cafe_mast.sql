DELIMITER $$

DROP PROCEDURE IF EXISTS `get_cafe_mast`$$

CREATE PROCEDURE `get_cafe_mast`(IN i_cafeid VARCHAR(32), IN i_visit BOOL, IN i_userid VARCHAR(32))
label_procedure: BEGIN
  DECLARE v_name VARCHAR(128);
  DECLARE v_type VARCHAR(32);
  DECLARE v_user_cnt INT;
  DECLARE v_content_cnt INT;
  DECLARE v_cash BIGINT;
  DECLARE v_point BIGINT;
  DECLARE v_reg_date TIMESTAMP;
  DECLARE v_addr1 VARCHAR(32);
  DECLARE v_addr2 VARCHAR(32);
  DECLARE v_addr3 VARCHAR(32);
  DECLARE v_addr4 VARCHAR(256);
  DECLARE v_zipcode VARCHAR(16);
  DECLARE v_info VARCHAR(4096);
  DECLARE v_last_visit TIMESTAMP;

  DECLARE v_not_found BOOL DEFAULT FALSE;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_not_found = TRUE;

  -- read cafe mast info
  SELECT `name`, `type`, user_cnt, content_cnt, cash, `point`, IFNULL(addr1, ''), IFNULL(addr2, ''), IFNULL(addr3, ''), IFNULL(addr4, ''), IFNULL(zipcode, ''), reg_date, info
    FROM cafe_mast WHERE cafeid = i_cafeid
    INTO v_name, v_type, v_user_cnt, v_content_cnt, v_cash, v_point, v_addr1, v_addr2, v_addr3, v_addr4, v_zipcode, v_reg_date, v_info;
  IF v_not_found THEN
    SELECT -1 AS errno, 'Cafe not found' AS errstr;
    LEAVE label_procedure;
  END IF;

  -- 결과 반환
  SELECT 0 errno, i_cafeid cafeid, v_name `name`, v_type `type`, v_user_cnt user_cnt, v_content_cnt content_cnt, v_cash cash, v_point `point`, v_reg_date reg_date,
    v_addr1 addr1, v_addr2 addr2, v_addr3 addr3, v_addr4 addr4, v_zipcode zipcode, v_info cafe_info_json;

  IF NOT i_visit THEN
    -- 사용자 방문을 위한 실행이 아니면 종료
    LEAVE label_procedure;
   END IF;

   -- 사용자 방문 처리
  SET v_not_found = FALSE;
  SELECT last_visit FROM cafe_user_mast WHERE cafeid = i_cafeid AND userid = i_userid LIMIT 1 INTO v_last_visit;
  IF v_not_found THEN
    -- 방문자가 카페 멤버가 아니면 종료
    LEAVE label_procedure;
  END IF;

  -- 카페 멤버 방문 시각 갱신
  UPDATE cafe_user SET last_visit = NOW() WHERE cafeid = i_cafeid AND userid = i_userid;

  IF DATE(NOW()) = DATE(v_last_visit) THEN
    -- 오늘 이미 방문 한 기록이 있으면 종료
    LEAVE label_procedure;
  END IF;

  -- 카페 방문 횟수 갱신
  INSERT INTO cafe_monthly (`month`, cafeid, visit_cnt)
    VALUES (DATE_FORMAT(NOW(), '%Y%m'), i_cafeid, 1)
    ON DUPLICATE KEY UPDATE visit_cnt = visit_cnt + 1;

  END$$

DELIMITER ;
