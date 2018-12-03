DELIMITER $$

DROP PROCEDURE IF EXISTS `get_content_list`$$

CREATE PROCEDURE `get_content_list`(IN i_cafeid VARCHAR(32), IN i_boardid VARCHAR(32), IN i_userid VARCHAR(32), IN i_size INT, IN i_search_type VARCHAR(32), IN i_search_str VARCHAR(128), IN i_last_ownerno INT, IN i_last_sequence INT)
label_procedure: BEGIN
  DECLARE v_boardid VARCHAR(32);
  DECLARE v_globalno BIGINT;
  DECLARE v_ownerno INT;
  DECLARE v_sequence INT;
  DECLARE v_userid VARCHAR(32);
  DECLARE v_nickname VARCHAR(32);
  DECLARE v_title VARCHAR(256);
  DECLARE v_deleted TINYINT;
  DECLARE v_reg_time TIMESTAMP;
  DECLARE v_edit_time TIMESTAMP;
  DECLARE v_view_cnt INT;
  DECLARE v_comment_cnt INT;
  DECLARE v_info VARCHAR(4096);
  DECLARE v_target_nickname VARCHAR(32);
  DECLARE v_count INT DEFAULT 0;
  DECLARE v_srch_str VARCHAR(160) DEFAULT CONCAT('%', i_search_str, '%');
  DECLARE v_not_found BOOL DEFAULT FALSE;
  DECLARE cur_total_nosrch CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_board_nosrch CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND boardid = i_boardid AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_total_nickname CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND nickname LIKE CONCAT('%', v_srch_str, '%') AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_board_nickname CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND boardid = i_boardid AND nickname LIKE CONCAT('%', v_srch_str, '%') AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_total_title CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND title LIKE CONCAT('%', v_srch_str, '%') AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_board_title CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND boardid = i_boardid AND title LIKE CONCAT('%', v_srch_str, '%') AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_total_all CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND (nickname LIKE CONCAT('%', v_srch_str, '%') OR title LIKE CONCAT('%', v_srch_str, '%') OR content LIKE CONCAT('%', v_srch_str, '%')) AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE cur_board_all CURSOR FOR SELECT boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = i_cafeid AND boardid = i_boardid AND (nickname LIKE CONCAT('%', v_srch_str, '%') OR title LIKE CONCAT('%', v_srch_str, '%') OR content LIKE CONCAT('%', v_srch_str, '%')) AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence ORDER BY ownerno DESC, sequence;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_not_found = TRUE;

  IF i_boardid = 'total' THEN
    IF i_search_type = 'none' THEN
      -- OPEN cur_total_nosrch;
      SELECT 0 errno, boardid, globalno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname
        FROM content WHERE cafeid = i_cafeid AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - i_last_ownerno) * 100000) + i_last_sequence
        ORDER BY ownerno DESC, sequence LIMIT 10;
      LEAVE label_procedure;
    ELSEIF i_search_type = 'nickname' THEN
      OPEN cur_total_nickname;
    ELSEIF i_search_type = 'title' THEN
      OPEN cur_total_title;
    ELSEIF i_search_type = 'all' THEN
      OPEN cur_total_all;
    ELSE
      SELECT -1 errno, 'invalid search type' errstr;
      LEAVE label_procedure;
    END IF;
  ELSE
    IF i_search_type = 'none' THEN
      OPEN cur_board_nosrch;
    ELSEIF i_search_type = 'nickname' THEN
      OPEN cur_board_nickname;
    ELSEIF i_search_type = 'title' THEN
      OPEN cur_board_title;
    ELSEIF i_search_type = 'all' THEN
      OPEN cur_board_all;
    ELSE
      SELECT -2 errno, 'invalid search type' errstr;
      LEAVE label_procedure;
    END IF;
  END IF;

  read_loop_board: LOOP
    IF i_boardid = 'total' THEN
      IF i_search_type = 'none' THEN
        FETCH cur_total_nosrch INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      ELSEIF i_search_type = 'nickname' THEN
        FETCH cur_total_nickname INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      ELSEIF i_search_type = 'title' THEN
        FETCH cur_total_title INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      ELSEIF i_search_type = 'all' THEN
        FETCH cur_total_all INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      END IF;
    ELSE
      IF i_search_type = 'none' THEN
        FETCH cur_board_nosrch INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      ELSEIF i_search_type = 'nickname' THEN
        FETCH cur_board_nickname INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      ELSEIF i_search_type = 'title' THEN
        FETCH cur_board_title INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      ELSEIF i_search_type = 'all' THEN
        FETCH cur_board_all INTO v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
      END IF;
    END IF;

    IF v_not_found THEN
      LEAVE read_loop_board;
    END IF;

    SELECT 0 errno, v_boardid, v_globalno, v_ownerno, v_sequence, v_userid, v_nickname, v_title, v_deleted, v_reg_time, v_edit_time, v_view_cnt, v_comment_cnt, v_info, v_target_nickname;
    SET v_count = v_count + 1;
    IF v_count >= i_size THEN
      LEAVE read_loop_board;
    END IF;
  END LOOP;

  IF i_boardid = 'total' THEN
    IF i_search_type = 'none' THEN
      CLOSE cur_total_nosrch;
    ELSEIF i_search_type = 'nickname' THEN
      CLOSE cur_total_nickname;
    ELSEIF i_search_type = 'title' THEN
      CLOSE cur_total_title;
    ELSEIF i_search_type = 'all' THEN
      CLOSE cur_total_all;
    END IF;
  ELSE
    IF i_search_type = 'none' THEN
      CLOSE cur_board_nosrch;
    ELSEIF i_search_type = 'nickname' THEN
      CLOSE cur_board_nickname;
    ELSEIF i_search_type = 'title' THEN
      CLOSE cur_board_title;
    ELSEIF i_search_type = 'all' THEN
      CLOSE cur_board_all;
    END IF;
  END IF;
END$$

DELIMITER ;
