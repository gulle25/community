DELIMITER $$

DROP PROCEDURE IF EXISTS `get_cafe_info`$$

CREATE PROCEDURE `get_cafe_info`(IN i_cafeid VARCHAR(32))
label_procedure: BEGIN
  DECLARE v_role_info VARCHAR(4096);
  DECLARE v_roleno BIGINT;
  DECLARE v_role_name VARCHAR(32);
  DECLARE v_role_default INT;
  DECLARE v_board_info VARCHAR(4096);
  DECLARE v_boardid VARCHAR(32);
  DECLARE v_board_name VARCHAR(32);
  DECLARE v_board_type VARCHAR(32);
  DECLARE v_board_view VARCHAR(32);
  DECLARE v_board_permission_list VARCHAR(512);
  DECLARE v_board_permission_read VARCHAR(512);
  DECLARE v_board_permission_write VARCHAR(512);
  DECLARE v_board_permission_comment VARCHAR(512);
  DECLARE v_board_additional_info VARCHAR(4096);
  DECLARE v_count INT;

  DECLARE v_not_found BOOL DEFAULT FALSE;
  DECLARE cur_role CURSOR FOR SELECT roleno, `name`, `default` FROM cafe_role WHERE cafeid = i_cafeid;
  DECLARE cur_board CURSOR FOR SELECT boardid, `name`, `type`, `view`, permission_list, permission_read, permission_write, permission_comment, info FROM board_mast WHERE cafeid = i_cafeid;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_not_found = TRUE;

  -- read role info
  OPEN cur_role;
  SET v_role_info = '{';
  SET v_not_found = FALSE;
  SET v_count = 0;

  read_loop_role: LOOP
    FETCH cur_role INTO v_roleno, v_role_name, v_role_default;
    IF v_not_found THEN
      LEAVE read_loop_role;
    END IF;

    IF v_count > 0 THEN
      SET v_role_info = CONCAT(v_role_info, ',');
    END IF;
    SET v_count = v_count + 1;

    SET v_role_info = CONCAT(v_role_info, '"', v_roleno, '":{"name":"', v_role_name, '","default":', v_role_default, '}');
  END LOOP;

  CLOSE cur_role;
  SET v_role_info = CONCAT(v_role_info, '}');

  -- read board info
  OPEN cur_board;
  SET v_board_info = '{';
  SET v_not_found = FALSE;
  SET v_count = 0;

  read_loop_board: LOOP
    FETCH cur_board INTO v_boardid, v_board_name, v_board_type, v_board_view, v_board_permission_list, v_board_permission_read, v_board_permission_write, v_board_permission_comment, v_board_additional_info;
    IF v_not_found THEN
      LEAVE read_loop_board;
    END IF;

    IF v_count > 0 THEN
      SET v_board_info = CONCAT(v_board_info, ',');
    END IF;
    SET v_count = v_count + 1;

    SET v_board_info = CONCAT(v_board_info, '"', v_boardid, '":{"name":"', json_encode(v_board_name), '","type":"', v_board_type,
      '","view":"', v_board_view, '","permission":{"list":', v_board_permission_list, ',"read":', v_board_permission_read,
       ',"write":', v_board_permission_write, ',"comment":', v_board_permission_comment, '},"info":', v_board_additional_info, '}');
  END LOOP;

  CLOSE cur_board;
  SET v_board_info = CONCAT(v_board_info, '}');

  -- 결과 반환
  SELECT 0 errno, i_cafeid cafeid, v_role_info role_info_json, v_board_info board_info_json;

  END$$

DELIMITER ;
